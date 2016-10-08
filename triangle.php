<?php

class trinage
{
    public $lines = array(
        array("A", "B", "H"),
        array("A", "C", "G", "I"),
        array("A", "D", "F", "J"),
        array("A", "E", "K"),
        array("B", "C", "D", "E"),
        array("H", "G", "F", "E"),
        array("H", "I", "J", "K")
    );

    public $letters = array(
        "A", "B", "C", "D",
        "E", "F", "G", "H",
        "I", "J", "K"
    );

    // 判断两点一线
    private function twoPointInOneLine($letter1, $letter2)
    {
        foreach ($this->lines as $l) {
            if (in_array($letter1, $l) && in_array($letter2, $l)) {
                return true;
            }
        }

        return false;
    }

    // 判断三点一线
    private function threePointInOneLine($letter1, $letter2, $letter3)
    {
        foreach ($this->lines as $l) {
            if (in_array($letter1, $l) && in_array($letter2, $l) && in_array($letter3, $l)) {
                return true;
            }
        }

        return false;
    }

    // 判断三点是否构成一个三角形
    private function isTriangle($letter1, $letter2, $letter3)
    {
        if ($this->twoPointInOneLine($letter1, $letter2) &&
            $this->twoPointInOneLine($letter2, $letter3) &&
            $this->twoPointInOneLine($letter1, $letter3) &&
            (!$this->threePointInOneLine($letter1, $letter2, $letter3))
        ) {
            return true;
        } else {
            return false;
        }
    }

    private function testTwoPointInOneLine()
    {
        var_dump($this->twoPointInOneLine("A", "B"));
        echo PHP_EOL;
        var_dump($this->twoPointInOneLine("D", "K"));
        echo PHP_EOL;
    }

    private function testthreePointInOneLine()
    {
        var_dump($this->isTriangle("A", "B", "C"));
        echo PHP_EOL;
        var_dump($this->isTriangle("E", "J", "K"));
        echo PHP_EOL;

    }

    //  计算出每个点都在哪些三角形上面,
    private function getTrinaglePossible($letter)
    {
        $neibor_letters = array();
        foreach ($this->lines as $l) {

            // 找出有A的线段
            if (in_array($letter, $l)) {
                foreach ($l as $a) {
                    if ($a != $letter) {
                        $neibor_letters[] = $a;
                    }
                }
            }

        }

        $neibor_letters = array_unique($neibor_letters);
        $pairs = $this->getPairsFromPoints($neibor_letters);
        $possible_triangle = array();
        foreach ($pairs as $p) {
            $possible_triangle[] = array($letter, $p[0], $p[1]);
        }

        return $possible_triangle;
    }

    // 计算两个点可以构成多少个组合
    private function getPairsFromPoints($arr)
    {
        if (count($arr) == 2) {
            return array($arr);
        } else {
            $pairs = array();
            $firstLetter = $arr[0];
            foreach (array_slice($arr, 1) as $letter) {
                $pairs[] = array($firstLetter, $letter);
            }
            $combines = $this->getPairsFromPoints(array_slice($arr, 1));
            return array_merge($pairs, $combines);
        }
    }

    private function testPointCombination()
    {
        foreach ($this->getPairsFromPoints(array("A", "H", "C", "D", "E")) as $pair) {
            echo implode(",", $pair) . PHP_EOL;
        }
    }

    private function triangleFromPoint($letter)
    {
        $ps = $this->getTrinaglePossible($letter);
        $pairs = array();
        foreach ($ps as $p) {
            if ($this->isTriangle($p[0], $p[1], $p[2])) {
                sort($p, SORT_STRING);
                $pairs[] = implode("", $p);
            }
        }

        return $pairs;
    }

    public function run()
    {
        $duplicate_pairs = array();

        // 计算出每个点都在哪些三角形上面, 然后根据三角形定点排序, 最后排除重复.
        foreach ($this->letters as $letter) {
            $pairs = $this->triangleFromPoint($letter);
            // print_r($pairs);
            foreach ($pairs as $p) {
                $duplicate_pairs[] = $p;
            }
        }

        $result = array_unique($duplicate_pairs);
        echo count($result);

    }
}

$tri = new trinage();
$tri->run();
