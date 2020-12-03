<?php


namespace DPodsiadlo\SvgCharts\Charts;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;

class LineChart extends Chart
{

    protected $options = [
        'colors' => ['#32638e'],
        'axisColor' => '#4a4a4c',
        'axisWidth' => 2,
        'gridColor' => '#9c9c9b',
        'gridWidth' => 1,
        'valueGroups' => 5,
        'width' => 1600,
        'height' => 900,
        'margin' => 10,
        'margins' => []
    ];


    private $min = null;
    private $max = null;


    public function __construct($data, $options)
    {
        parent::__construct($data, $options);

        $this->min = PHP_INT_MAX;
        $this->max = -PHP_INT_MAX;

        for ($i = 0; $i < count($this->data['data']); $i++) {
            foreach ($this->data['data'][$i] as $val) {
                $this->min = min($this->min, $val);
                $this->max = max($this->max, $val);
            }
        }

        if ($this->min < $this->max) {
            $exp = floor(log($this->max, 10));
            $base = pow(10, $exp - 1);

            $this->max = ceil($this->max / $base) * $base;
            $this->min = floor($this->min / $base) * $base;
        } else {
            $this->min = 0;
            $this->max = 0;

        }
    }

    /**
     * @return string
     */
    public function render()
    {
        return View::make("svg-charts::line-chart", array_merge([
            'paths' => $this->paths(),
            'grid' => $this->grid(),
            'isEmpty' => $this->isEmpty()
        ], $this->options, $this->dimensions()
        ))->render();
    }


    /**
     * @return bool
     */
    private function isEmpty()
    {
        foreach ($this->data['data'] as $data) {
            if (!empty($data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    private function dimensions()
    {
        return [
            'axisX0' => $this->axisX0,
            'axisY0' => $this->axisY0,
            'axisX1' => $this->axisX1,
            'axisY1' => $this->axisY1
        ];
    }

    /**
     * @return array
     */
    private function grid()
    {
        return [
            'X' => $this->getXItems(),
            'Y' => $this->getYItems()
        ];
    }

    /**
     * @return array
     */
    private function paths()
    {
        $res = [];

        $hth = $this->height * .9 - 2 * $this->margin;

        $xPositions = $this->getXPositions(count($this->data['data']));

        foreach ($this->data['data'] as $i => $data) {

            $path = "M" . $this->axisX0 . " " . $this->axisY0;

            foreach ($data as $i => $value) {
                $y = $this->axisY0 - ($value - $this->min) / ($this->max - $this->min) * $hth;

                $path .= " L" . $xPositions[$i] . " " . $y;
            }

            $path .= " L" . $xPositions[$i] . " " . $this->axisY0;


            $res[] = $path;
        }


        return $res;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'axisX0':
                return $this->margin + $this->width * 0.1;
                break;
            case 'axisY0':
                return $this->height * 0.9 - $this->margin;
                break;
            case 'axisX1':
                return $this->width - $this->margin;
                break;
            case 'axisY1':
                return $this->margin;
                break;
            default:
                return $this->options[$name];
        }
    }

    protected function getXItems()
    {
        $xPositions = $this->getXPositions(count($this->data['labels']));

        return array_map(function ($x, $index) {
            return [
                'x' => $x,
                'text' => $this->data['labels'][$index]
            ];
        }, $xPositions, array_keys($xPositions));
    }

    protected function getYItems()
    {
        $result = [];
        $valueStep = ($this->max - $this->min) / $this->valueGroups;
        $yStep = ($this->height * .9 - $this->margin) / $this->valueGroups;

        for ($i = 0; $i <= $this->valueGroups; $i++) {
            $result[] = [
                'y' => $this->height * 0.9 - $this->margin - $i * $yStep,
                'text' => $this->min + $i * $valueStep
            ];
        }

        return $result;
    }

    protected function getXPositions()
    {
        $x = $this->margin + ($this->width * 0.1);

        $gridSize = ($this->axisX0 + $this->axisX1) / array_sum($this->options['margins']);
        $result = [];

        foreach ($this->options['margins'] as $margin) {
            $result[] = $x;

            $x += $gridSize * $margin;
        }

        return $result;
    }

}
