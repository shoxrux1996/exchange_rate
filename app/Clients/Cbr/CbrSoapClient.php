<?php

namespace App\Clients\Cbr;

use App\Contracts\GetExchangeRatesClientInterface;
use App\Models\RateModel;
use Illuminate\Support\Carbon;
use SimpleXMLElement;

class CbrSoapClient implements GetExchangeRatesClientInterface
{
    private $client;

    public function __construct()
    {
        $wsdl = config('services.cbr.url') . '/DailyInfoWebServ/DailyInfo.asmx?wsdl';

        $soapClientOptions = [
            'encoding' => 'UTF-8',
            'trace' => false,
        ];

        try {
            $this->client = new \SoapClient(
                $wsdl,
                $soapClientOptions
            );
        } catch (\SoapFault $e) {
            logger()->error($e->getMessage());
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    public function getRate($fucntion, $params = [])
    {
        return $this->client->__soapCall($fucntion, [$params]);
    }

    public function getRateByDate(Carbon $date, string $quoteCurrencyCode, string $baseCurrencyCode)
    {
        try {
            $response = $this->getRate('GetCursOnDate', ['On_date' => $date->format('Y-m-d')]);
            $parsedData = $this->parseDataToCollection($response->GetCursOnDateResult->any, $date);

            if (isset($parsedData[$quoteCurrencyCode])) {
                return $parsedData[$quoteCurrencyCode];
            }
        } catch (\SoapFault $e) {
            logger()->error($e->getMessage());
            return null;
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
            return null;
        }
    }

    public function parseDataToCollection($data, $date): array
    {
        $data = (new SimpleXMLElement($data));

        $result = array();

        foreach ($data->ValuteData->ValuteCursOnDate as $item) {
            $item = (array) $item;
            $rate = floatval(str_replace(",", ".", $item['Vcurs']));
            $rateModel = new RateModel($date, floatval(intval($item['Vnom']) / $rate), $item['VchCode']);
            $result[$item['VchCode']] = $rateModel;
        }

        return $result;
    }
}
