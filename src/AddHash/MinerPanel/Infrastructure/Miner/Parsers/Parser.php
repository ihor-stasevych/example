<?php

namespace App\AddHash\MinerPanel\Infrastructure\Miner\Parsers;

use App\AddHash\MinerPanel\Domain\Miner\Parsers\ParserInterface;

class Parser implements ParserInterface
{
    public function normalizeData(string $line): array
    {
        $data = [];

        if ($line) {
            if (substr($line, 0, 1) == '{') {
                $data = json_decode($line, true);
            } else {
                $objects = explode('|', $line);

                foreach ($objects as $object) {
                    if (!$object) {
                        break;
                    }

                    $items = explode(',', $object);
                    $id = explode('=', $items[0], 2);
                    $name = $id[0];

                    if (strlen($name) == 0) {
                        $name = 'null';
                    }

                    $itemsArray = [];

                    foreach ($items as $item) {
                        $itemExplode = explode('=', $item);

                        if (count($itemExplode) < 2) {
                            $itemsArray[] = $itemExplode[0];
                        } else {
                            $itemsArray[$itemExplode[0]] = $itemExplode[1];
                        }
                    }

                    if (count($id) == 1 || !ctype_digit($id[1])) {
                        $data[$name] = $itemsArray;
                    } else {
                        $data[$name][] = $itemsArray;
                    }
                }
            }
        }

        return $data;
    }
}