@props(['size' => 24, 'color' => 'gray'])

@php
    switch ($color) {
        case 'gray':
            $col = "#374151";
            break;
        case 'white':
            $col = "#ffffff";
            break;
        default:
            $col = "#374151";
            break;
    }
@endphp

<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
     width="{{ $size }}" height="{{ $size }}"
     viewBox="0 0 172 172"
     style=" fill:#000000;"><g fill="{{ $col }}" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><path d="M0,172v-172h172v172z" fill="none"></path><g fill="#cccccc"><path d="M49.71875,14.78125c-2.28438,0 -4.03125,1.74687 -4.03125,4.03125c0,2.28438 1.74687,4.03125 4.03125,4.03125h9.40625v9.40625c0,2.28437 1.74687,4.03125 4.03125,4.03125h94.46667c1.74687,0 3.35833,0.8052 4.29895,2.28333c0.94063,1.47812 1.34375,3.2271 0.67188,4.8396l-18.40833,55.36145c-2.01562,6.04688 -7.6599,10.07813 -13.97552,10.07813h-71.0849c-7.39062,0 -13.4375,6.04688 -13.4375,13.4375c0,7.39063 6.04688,13.4375 13.4375,13.4375h50.39063c4.8375,0 8.73438,3.89687 8.73438,8.73438c0,2.28438 1.74688,4.03125 4.03125,4.03125c2.28437,0 4.03125,-1.74687 4.03125,-4.03125c0,-9.27187 -7.525,-16.79687 -16.79687,-16.79687h-50.39062c-2.95625,0 -5.375,-2.41875 -5.375,-5.375c0,-2.95625 2.41875,-5.375 5.375,-5.375h71.0849c9.80937,0 18.54322,-6.3151 21.63385,-15.58697l18.40833,-55.36407c1.34375,-4.16562 0.67397,-8.6 -1.87915,-12.09375c-2.41875,-3.62813 -6.45,-5.6427 -10.75,-5.6427h-90.43542v-9.40625c0,-2.28438 -1.74688,-4.03125 -4.03125,-4.03125zM20.15625,55.09375c-2.28438,0 -4.03125,1.74687 -4.03125,4.03125c0,2.28438 1.74687,4.03125 4.03125,4.03125h26.875c2.28438,0 4.03125,-1.74687 4.03125,-4.03125c0,-2.28438 -1.74687,-4.03125 -4.03125,-4.03125zM5.375,81.86902c-0.26875,0 -0.53698,0.03254 -0.80573,0.09973c-0.26875,0 -0.53698,0.13333 -0.80573,0.2677c-0.26875,0.13438 -0.40312,0.2698 -0.67187,0.40417c-0.26875,0.13438 -0.40365,0.26928 -0.53802,0.53803c-0.80625,0.67188 -1.2099,1.74635 -1.2099,2.82135c0,1.075 0.40365,2.14947 1.2099,2.82135c0.13437,0.26875 0.40365,0.40365 0.53802,0.53803c0.26875,0.13437 0.40313,0.2698 0.67188,0.40417c0.26875,0.13437 0.53698,0.13333 0.80573,0.2677h0.80573c0.26875,0 0.53698,0.00053 0.80573,-0.13385c0.26875,0 0.53698,-0.13595 0.80573,-0.27032c0.26875,-0.13437 0.5375,-0.26718 0.67188,-0.40155c0.26875,-0.13437 0.40365,-0.26928 0.53802,-0.53803c0.80625,-0.5375 1.2099,-1.6125 1.2099,-2.6875c0,-1.075 -0.40365,-2.14947 -1.2099,-2.82135l-0.53802,-0.53803c-0.26875,-0.13438 -0.40313,-0.2698 -0.67187,-0.40417c-0.26875,-0.13438 -0.53698,-0.13333 -0.80573,-0.2677c-0.26875,-0.06719 -0.53698,-0.09973 -0.80573,-0.09973zM26.875,81.96875c-2.28438,0 -4.03125,1.74688 -4.03125,4.03125c0,2.28437 1.74687,4.03125 4.03125,4.03125h40.3125c2.28437,0 4.03125,-1.74688 4.03125,-4.03125c0,-2.28437 -1.74688,-4.03125 -4.03125,-4.03125zM59.125,143.78125c-7.39062,0 -13.4375,6.04688 -13.4375,13.4375c0,7.39063 6.04688,13.4375 13.4375,13.4375c7.39063,0 13.4375,-6.04687 13.4375,-13.4375c0,-7.39062 -6.04687,-13.4375 -13.4375,-13.4375zM106.24023,146.46875c-1.02461,0 -2.03242,0.40365 -2.77148,1.2099c-2.55313,2.55312 -3.8974,5.91198 -3.8974,9.5401c0,7.39063 6.04688,13.4375 13.4375,13.4375c7.39063,0 13.4375,-6.04687 13.4375,-13.4375c0,-2.28438 -1.74687,-4.03125 -4.03125,-4.03125c-2.28437,0 -4.03125,1.74687 -4.03125,4.03125c0,2.95625 -2.41875,5.375 -5.375,5.375c-2.95625,0 -5.375,-2.41875 -5.375,-5.375c0,-1.47813 0.53907,-2.82292 1.61407,-3.76355c1.34375,-1.6125 1.34165,-4.16405 -0.13647,-5.77655c-0.80625,-0.80625 -1.84661,-1.2099 -2.87122,-1.2099zM59.125,151.84375c2.95625,0 5.375,2.41875 5.375,5.375c0,2.95625 -2.41875,5.375 -5.375,5.375c-2.95625,0 -5.375,-2.41875 -5.375,-5.375c0,-2.95625 2.41875,-5.375 5.375,-5.375z"></path></g></g>
</svg>
