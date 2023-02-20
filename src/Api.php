<?php

namespace App;

use DateTime;
use DateInterval;

class Api
{
    private string $subdomain;
    private ?array $config;
    private string $secret = 'A8uCD8qaPL38o9rfqf5vKwsc3HvEPqTjsupIN48QNMle1SgsahzZmCdPQ9NAYMdi';
    private string $integration_id = '3f28acbc-c5d5-437d-a2ba-60fb00ec6584';
    private string $auth = 'def50200eda0d43346cab96de56c083719e10dd2b451a15d213af913477ac3de815f7f78507b0fae6c48c2ab7d273fa9c56e04ecaef5f86d87c2f09cd6d3b7ff42d2a5da809efcfa14cce2506abbdb037905aec4effcc7fa65308a885c421c2e439ac972bf3f53877a957ff85a196a12925d51b4d3a38711dfa9f1b3fc2eb85110830a47c1c47c7d133c226d5b168fc386e0b18d4fdf441b7b9eee2234ac608aa270a76bf4cf3e51be6fba3a2ee93cd9e067728eed14be2fa7e0057570911966a9b897b1c10a6d402f217272cef89d349590f0bf3e6c8f8d74cfb28ab5be0beb077b7094bde61daf6bc2a843c0e1f08979410a036d5834212eea778f264d9b3579e4d85c716812fcb7992fba4fe3657d1e7af09b383486c57fe2da2a5fa3b79df319345bf09bbef5e64ee74c6ece753ed20523debaba51973a640e289dabb6f3ff49915956454b5f677dc284fd903d009718691932d7ae9ea6fd57b63b7426941cc2679dae7db6eb20d6693eb3aa088499728e591d4a66759fa438e562e94320b2402242f89fc2cba5f17cccddceb37ca567c07c23848a4b72e11b22ade133873d1f100b64a88d86b4f30e138043cf046fe08d31f8b9df3c927c513bbaa56fabfb1fdc63bba9bf100a17c7ba005ec3bd84468a55f159';

    public function __construct(string $subdomain)
    {
        session_start();
        $this->subdomain = $subdomain;
        $this->config = $this->getConfig();
    }

    public function addLead(array $data): ?array
    {
        $data = 
        [
            [
                "created_by" => 0,
                "price" => $data['price'],
                "_embedded" =>[
                    "contacts" => [
                        [
                            "first_name" => $data['name'],
                            "custom_fields_values"=>[
                                [
                                   "field_id"=>66186,
                                   "values"=>[
                                        [
                                            "enum_id"=>193200,
                                            "value"=>$data['email']
                                        ]
                                   ]
                                ],
                                [
                                   "field_id"=>66192,
                                   "values"=>[
                                      [
                                            "enum_id"=>193226,
                                            "value"=>$data['phone']
                                      ]
                                   ]
                                ]
                            ]
                        ]
                    ]
                ] 
            ]
        ];
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->config['access_token']
        ];

        $location = "/api/v4/leads/complex";

        $result = $result = $this->curl($location,$data,$headers);

        return $result;
    }

    private function getConfig(): ?array
    {
        $now = new DateTime();
        $location = "https://$this->subdomain.amocrm.ru/oauth2/access_token";

        if(!empty($_SESSION['config']['expired']) &&  $_SESSION['config']['expired']->getTimeStamp() < $now->getTimeStamp()){

            $result =  $_SESSION['config'];

        }else if(empty($_SESSION['config']['expired'])){

            $data = [
                'client_id' => $this->integration_id,
                'client_secret' => $this->secret,
                'grant_type' => 'authorization_code',
                'code' => $this->auth,
                'redirect_uri' => 'https://www.amocrm.ru'
            ];

            $result = $this->curl($location,$data,['Content-Type:application/json']);
            $result['expired'] = $now->add(new DateInterval('PT'.$result['expires_in'].'S'));
        }else{
            $data = [
                'client_id' => $this->integration_id,
                'client_secret' => $this->secret,
                'grant_type'    => 'refresh_token',
                'refresh_token' => $_SESSION['config']['refresh_token'],
                'redirect_uri' => 'https://www.amocrm.ru'
            ];

            $result = $this->curl($location,$data,['Content-Type:application/json']);
            $result['expired'] = $now->add(new DateInterval('PT'.$result['expires_in'].'S'));
        }

        $_SESSION['config'] = $result;

        return $result;
    }

    private function curl(string $location, ?array $data, array $headers): ?array
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $location);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl);

        return json_decode($out, true);
    }
}