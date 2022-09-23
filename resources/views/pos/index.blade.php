@extends('layouts.app')
@section('content')

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-12 mx-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-lg-6 my-2">
                                <div class="card w-100  dagpacket_orange service_btns">
                                    <a class="text-white btn" href="{{ route('pos.service.index') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="80" height="150"
                                            viewBox="0 0 172 172" style=" fill:#000000;">
                                            <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1"
                                                stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10"
                                                stroke-dasharray="" stroke-dashoffset="0" font-family="none"
                                                font-weight="none" font-size="none" text-anchor="none"
                                                style="mix-blend-mode: normal">
                                                <path d="M0,172v-172h172v172z" fill="none"></path>
                                                <g fill="#ffffff">
                                                    <path
                                                        d="M110.1875,5.375c-26.67344,0 -48.375,21.70156 -48.375,48.375c0,26.67344 21.70156,48.375 48.375,48.375c26.67344,0 48.375,-21.70156 48.375,-48.375c0,-26.67344 -21.70156,-48.375 -48.375,-48.375zM110.1875,10.75c23.70913,0 43,19.29088 43,43c0,23.70913 -19.29087,43 -43,43c-23.70912,0 -43,-19.29087 -43,-43c0,-23.70912 19.29088,-43 43,-43zM110.1875,18.8125c-19.264,0 -34.9375,15.6735 -34.9375,34.9375c0,19.264 15.6735,34.9375 34.9375,34.9375c19.264,0 34.9375,-15.6735 34.9375,-34.9375c0,-6.88806 -2.01285,-13.55785 -5.82642,-19.28491c-0.82237,-1.23356 -2.49593,-1.56774 -3.72681,-0.74536c-1.23625,0.82238 -1.57042,2.49056 -0.74536,3.72681c3.21963,4.84019 4.92358,10.47965 4.92358,16.30347c0,16.29969 -13.26281,29.5625 -29.5625,29.5625c-16.29969,0 -29.5625,-13.26281 -29.5625,-29.5625c0,-16.29969 13.26281,-29.5625 29.5625,-29.5625c0.83313,0 1.65273,0.05879 2.46704,0.12598c1.50769,0.11825 2.77115,-0.97842 2.89746,-2.45654c0.12094,-1.47812 -0.97842,-2.77652 -2.45654,-2.89746c-0.95944,-0.08063 -1.92433,-0.14697 -2.90796,-0.14697zM123.70898,21.77295c-1.04787,-0.03238 -2.06119,0.55237 -2.52478,1.56421c-0.6235,1.34912 -0.03162,2.94852 1.3175,3.56934c1.68775,0.774 3.3459,1.74746 4.92883,2.88696c0.47569,0.34131 1.02133,0.50391 1.56421,0.50391c0.83581,0 1.66209,-0.38973 2.18884,-1.11804c0.86538,-1.204 0.58986,-2.87974 -0.61414,-3.7478c-1.85975,-1.34106 -3.82167,-2.49005 -5.82117,-3.41187c-0.33862,-0.1552 -0.69002,-0.23591 -1.03931,-0.2467zM110.1875,29.5625c-1.4835,0 -2.6875,1.204 -2.6875,2.6875v2.6875c-5.93669,0 -10.75,4.81331 -10.75,10.75c0,5.93669 4.81331,10.75 10.75,10.75h5.375c2.96969,0 5.375,2.40531 5.375,5.375c0,2.96969 -2.40531,5.375 -5.375,5.375v-5.375c0,-1.4835 -1.204,-2.6875 -2.6875,-2.6875c-1.4835,0 -2.6875,1.204 -2.6875,2.6875v5.375c-2.96969,0 -5.375,-2.40531 -5.375,-5.375c0,-1.4835 -1.204,-2.6875 -2.6875,-2.6875c-1.4835,0 -2.6875,1.204 -2.6875,2.6875c0,5.93669 4.81331,10.75 10.75,10.75v2.6875c0,1.4835 1.204,2.6875 2.6875,2.6875c1.4835,0 2.6875,-1.204 2.6875,-2.6875v-2.6875c5.93669,0 10.75,-4.81331 10.75,-10.75c0,-5.93669 -4.81331,-10.75 -10.75,-10.75v-5.375c0,-1.4835 -1.204,-2.6875 -2.6875,-2.6875c-1.4835,0 -2.6875,1.204 -2.6875,2.6875v5.375c-2.96969,0 -5.375,-2.40531 -5.375,-5.375c0,-2.96969 2.40531,-5.375 5.375,-5.375h5.375c2.96969,0 5.375,2.40531 5.375,5.375c0,1.4835 1.204,2.6875 2.6875,2.6875c1.4835,0 2.6875,-1.204 2.6875,-2.6875c0,-5.93669 -4.81331,-10.75 -10.75,-10.75v-2.6875c0,-1.4835 -1.204,-2.6875 -2.6875,-2.6875zM49.25159,94.92859c-1.62229,-0.03578 -3.25309,0.04405 -4.87634,0.2362l-31.88257,3.7373c-3.69531,0.43537 -6.55775,3.32234 -7.04419,6.91821c-0.05106,0.37356 -0.07617,0.75729 -0.07349,1.14429v33.96643c0,4.45319 3.60675,8.0625 8.05725,8.0625c0.66113,0 1.32082,-0.08021 1.96313,-0.24146l17.61572,-4.40393c4.52038,-1.14756 9.30438,-0.57349 13.427,1.61145l33.81421,17.81518c7.01438,3.67112 15.38321,3.67112 22.39758,0l57.20386,-29.5625c6.16781,-3.21963 8.55788,-10.83365 5.33826,-17.00684c-0.83581,-1.60444 -2.0058,-3.0053 -3.43286,-4.11523l-0.13123,-0.07874c-3.04494,-2.36231 -7.01181,-3.1849 -10.74475,-2.23083l-36.09228,9.34851c-1.9565,-5.80769 -7.59395,-9.55049 -13.69995,-9.10706l-5.87891,0.43042c-2.26556,0.14244 -4.52646,-0.29483 -6.57702,-1.26501l-25.27405,-11.93103c-4.43639,-2.09222 -9.24252,-3.22055 -14.10937,-3.32788zM49.02063,100.29834c4.13329,0.10507 8.21388,1.08054 11.97302,2.87647l25.27405,11.93103c2.92131,1.37869 6.15245,1.98174 9.37476,1.74793l5.87891,-0.43042c4.43706,-0.32787 8.29627,3.00862 8.62414,7.44837c0.01613,0.20425 0.02099,0.40989 0.021,0.61413v1.1023c-0.00269,1.05619 -0.62039,2.01336 -1.58521,2.44604l-8.05725,3.57458c-5.9555,2.66869 -12.73375,2.80961 -18.79675,0.39893l-13.43225,-5.375c-1.37869,-0.54825 -2.94235,0.12509 -3.4906,1.50647c-0.54825,1.38137 0.1251,2.9476 1.50647,3.49585l13.43225,5.375c7.44706,2.967 15.77764,2.79315 23.0957,-0.48291l8.05725,-3.57458c2.85144,-1.35719 4.64664,-4.25637 4.59289,-7.41687l36.65918,-9.51648c3.784,-1.22012 7.83713,0.8587 9.05457,4.64539c1.12875,3.5045 -0.56421,7.29518 -3.92627,8.79211l-0.02624,-0.07874l-57.20386,29.5625c-5.45293,2.84875 -11.95282,2.84875 -17.40576,0l-33.80896,-17.7417c-5.25406,-2.77081 -11.34713,-3.50593 -17.11182,-2.06811l-17.64197,4.40918c-1.4405,0.35744 -2.89964,-0.52263 -3.25439,-1.96313c-0.05913,-0.2365 -0.0868,-0.4798 -0.07874,-0.72437v-33.94543c-0.01075,-1.36794 1.01281,-2.52356 2.36731,-2.6875l31.76709,-3.73206c1.37936,-0.15923 2.76371,-0.22399 4.14148,-0.18897zM18.8125,107.5c-1.4835,0 -2.6875,1.204 -2.6875,2.6875c0,1.4835 1.204,2.6875 2.6875,2.6875h5.375c1.4835,0 2.6875,-1.204 2.6875,-2.6875c0,-1.4835 -1.204,-2.6875 -2.6875,-2.6875zM18.8125,120.9375c-1.4835,0 -2.6875,1.204 -2.6875,2.6875c0,1.4835 1.204,2.6875 2.6875,2.6875h5.375c1.4835,0 2.6875,-1.204 2.6875,-2.6875c0,-1.4835 -1.204,-2.6875 -2.6875,-2.6875zM18.8125,134.375c-1.4835,0 -2.6875,1.204 -2.6875,2.6875c0,1.4835 1.204,2.6875 2.6875,2.6875h5.375c1.4835,0 2.6875,-1.204 2.6875,-2.6875c0,-1.4835 -1.204,-2.6875 -2.6875,-2.6875z">
                                                    </path>
                                                </g>
                                            </g>
                                        </svg> Pago de servicios
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6 my-2">
                                <div class="card w-100 dagpacket_purple service_btns">
                                    <a class="text-white btn" href="{{ route('pos.recharge') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="80" height="150"
                                            viewBox="0 0 172 172" style=" fill:#000000;">
                                            <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1"
                                                stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10"
                                                stroke-dasharray="" stroke-dashoffset="0" font-family="none"
                                                font-weight="none" font-size="none" text-anchor="none"
                                                style="mix-blend-mode: normal">
                                                <path d="M0,172v-172h172v172z" fill="none"></path>
                                                <g fill="#ffffff">
                                                    <path
                                                        d="M53.75,17.2c-5.91146,0 -10.75,4.83854 -10.75,10.75v116.1c0,5.91146 4.83854,10.75 10.75,10.75h64.5c5.91146,0 10.75,-4.83854 10.75,-10.75v-19.35h-4.3v4.3h-77.4v-90.3h77.4v4.3h4.3v-15.05c0,-5.91146 -4.83854,-10.75 -10.75,-10.75zM53.75,21.5h64.5c3.58724,0 6.45,2.86276 6.45,6.45v6.45h-77.4v-6.45c0,-3.58724 2.86276,-6.45 6.45,-6.45zM68.68662,25.8c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM77.4,25.8c-0.77537,-0.01097 -1.49657,0.39641 -1.88746,1.06613c-0.39088,0.66972 -0.39088,1.49803 0,2.16775c0.39088,0.66972 1.11209,1.07709 1.88746,1.06613h17.2c0.77537,0.01097 1.49657,-0.39641 1.88746,-1.06613c0.39088,-0.66972 0.39088,-1.49803 0,-2.16775c-0.39088,-0.66972 -1.11209,-1.07709 -1.88746,-1.06613zM55.9,43c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM126.85,49.45c-18.9673,0 -34.4,15.4327 -34.4,34.4c0,18.9673 15.4327,34.4 34.4,34.4c18.9673,0 34.4,-15.4327 34.4,-34.4c0,-18.9673 -15.4327,-34.4 -34.4,-34.4zM55.9,51.6c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM126.85,53.75c16.598,0 30.1,13.502 30.1,30.1c0,16.598 -13.502,30.1 -30.1,30.1c-16.598,0 -30.1,-13.502 -30.1,-30.1c0,-16.598 13.502,-30.1 30.1,-30.1zM55.9,60.2c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM125.53984,66.26367v2.80928c-5.7362,0.48805 -9.19629,3.63948 -9.19629,8.44043c0,4.0678 2.5459,6.83646 7.24365,7.99531l1.95264,0.46611v8.66719c-2.82725,-0.3053 -4.67995,-1.93282 -4.8627,-4.21182h-4.96348c0.01935,4.7988 3.76112,8.01194 9.82197,8.37744v2.62871h2.80928v-2.64551c6.1232,-0.5074 9.64141,-3.66108 9.64141,-8.70498c0.00215,-4.29355 -2.45933,-6.91739 -7.62578,-8.11709l-2.01562,-0.42832v-8.23467c2.44025,0.32465 4.14742,1.99174 4.20762,4.08584h4.86269c-0.10105,-4.57735 -3.63941,-7.83275 -9.07031,-8.31865v-2.80928zM55.9,68.8c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM125.53984,73.28057v7.66778c-2.7262,-0.56975 -4.16982,-1.91108 -4.16982,-3.86328c0,-2.0769 1.75107,-3.66044 4.16982,-3.80449zM55.9,77.4c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM55.9,86c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM128.34492,86.50391c3.21425,0.62995 4.67793,1.92889 4.67793,4.10684c0,2.40155 -1.70663,3.88629 -4.67793,4.06904zM55.9,94.6c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM55.9,103.2c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM55.9,111.8c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM55.9,120.4c-1.18741,0 -2.15,0.96259 -2.15,2.15c0,1.18741 0.96259,2.15 2.15,2.15c1.18741,0 2.15,-0.96259 2.15,-2.15c0,-1.18741 -0.96259,-2.15 -2.15,-2.15zM47.3,133.3h77.4v10.75c0,3.58724 -2.86276,6.45 -6.45,6.45h-64.5c-3.58724,0 -6.45,-2.86276 -6.45,-6.45zM86,137.6c-2.37482,0 -4.3,1.92518 -4.3,4.3c0,2.37482 1.92518,4.3 4.3,4.3c2.37482,0 4.3,-1.92518 4.3,-4.3c0,-2.37482 -1.92518,-4.3 -4.3,-4.3z">
                                                    </path>
                                                </g>
                                            </g>
                                        </svg>

                                        Recargas
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
