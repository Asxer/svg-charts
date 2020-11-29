<svg id="svg" xmlns="http://www.w3.org/2000/svg" width="{{$width}}" height="{{$height}}">
    @if(!$isEmpty)
        <g>
            <line x1="{{$axisX0}}"
                  y1="{{$axisY0}}"
                  x2="{{$axisX1}}"
                  y2="{{$axisY0}}"
                  style="stroke:{{$axisColor}};stroke-width:{{$axisWidth}}"/>

            <line x1="{{$axisX0}}"
                  y1="{{$axisY0}}"
                  x2="{{$axisX0}}"
                  y2="{{$axisY1}}"
                  style="stroke:{{$axisColor}};stroke-width:{{$axisWidth}}"/>

            @foreach($grid['Y'] as $val)
                <line x1="{{$margin}}"
                      y1="{{$val['y']}}"
                      x2="{{$axisX1}}"
                      y2="{{$val['y']}}"
                      style="stroke:{{$gridColor}};stroke-width:{{$gridWidth}}"/>

                <text style="font-family: sans-serif; font-size: 20pt;"
                      x="{{$margin}}"
                      y="{{ $val['y'] + 25}}"
                      fill="{{ $axisColor }}"
                      text-anchor="start">
                    {{$val['text']}}
                </text>
            @endforeach

            @foreach($grid['X'] as $label)
                <line x1="{{$label['x']}}"
                      y1="{{$axisY0}}"
                      x2="{{$label['x']}}"
                      y2="{{$axisY0 + 5}}"
                      style="stroke:{{$gridColor}};stroke-width:{{$gridWidth}}"/>

                <text style="font-family: sans-serif; font-size: 14pt;"
                      x="{{$label['x']}}"
                      y="{{$axisY0 + 25}}"
                      fill="{{$axisColor}}"
                      text-anchor="middle">
                    {{$label['text']}}
                </text>
            @endforeach


        </g>

        @foreach($paths as $i => $path)
            <path d="{{$path}}" stroke="{{$colors[$i]}}" stroke-width="1" fill="{{$colors[$i]}}"/>
        @endforeach
    @else
        <text style="font-family: sans-serif; font-size: 25pt;" x="{{$width*.5}}" y="{{$height*.5}}" fill="{{$gridColor}}"
              text-anchor="middle">No data.
        </text>
    @endif
</svg>
