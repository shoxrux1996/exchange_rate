<?php

namespace App\Clients\Cbr;

use App\Contracts\GetExchangeRatesClientInterface;
use App\Models\RateModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class CbrXmlClient implements GetExchangeRatesClientInterface
{

    public function getRate($params = [])
    {
        $url = config('services.cbr.url');

        $response = Http::get($url . '/scripts/XML_daily.asp', $params);

        $response->throwUnlessStatus(200);

        return $response;
    }

    public function getRateByDate(Carbon $date, string $quoteCurrencyCode, string $baseCurrencyCode)
    {
        $parsedData = array();

        try {
            $response = $this->getRate(['date_req' => $date->format('d/m/Y')]);

            $parsedData = $this->parseDataToCollection($response->body(), $date);

            if(isset($parsedData[$quoteCurrencyCode]))
            {
                return $parsedData[$quoteCurrencyCode];
            }

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

        foreach ($data->Valute as $item) {
            $item = (array) $item;
            $rate = floatval(str_replace(",", ".", $item['Value']));
            $rateModel = new RateModel($date, floatval(intval($item['Nominal']) / $rate), $item['CharCode']);
            $result[$item['CharCode']] = $rateModel;
        }

        return $result;
    }
}
