<div class="form1 A" >
<label class="white-text" data-producto="tour" id="producto">Tours</label>
    <form  name="form" id="form" style="margin-left:10px; margin-right: 5px">
        <div class="form-group form-float">
            <div class="form-line">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <div >
                    <div class="input-field col s12">
                        <div ><span class="white-text">Destino</span></div>
                        <div class="col s12 @if($from === 'resultados') m12 @else m8 @endif divDestinos">
                            <select id="selectDestino" name="destino" class="selectDestino" style="width: 100%">
                                @isset($busqueda["destino"])
                                <option value="{{ $busqueda["destino"][0]["id"] }} {{ old('destino') }}" selected>{{ $busqueda["destino"][0]["text"] }}<option>
                                    @endisset
                            </select>
                        </div> 

                    </div>
                </div>
                <div class="input-field col s6 @if($from === 'resultados') m6 @else m4 @endif">
                    <div ><span for="adultos" class="white-text">Adultos +18</span></div>
                    <div >
                        <select id="adultos" class="browser-default form-control" name="adultos" id="adultos">
                            <option value="0">0</option>
                            <option value="1" @isset($busqueda) @if($busqueda["adultos"] ==1) selected @endif @endisset>1</option>
                            <option value="2" selected @isset($busqueda) @if($busqueda["adultos"] ==2) selected @endif @endisset>2</option>
                            <option value="3" @isset($busqueda) @if($busqueda["adultos"] ==3) selected @endif @endisset>3</option>
                            <option value="4" @isset($busqueda) @if($busqueda["adultos"] ==4) selected @endif @endisset>4</option>
                            <option value="5" @isset($busqueda) @if($busqueda["adultos"] ==5) selected @endif @endisset>5</option>
                            <option value="6" @isset($busqueda) @if($busqueda["adultos"] ==6) selected @endif @endisset>6</option>
                            <option value="7"@isset($busqueda) @if($busqueda["adultos"] ==7) selected @endif @endisset>7</option>
                            <option value="8" @isset($busqueda) @if($busqueda["adultos"] ==8) selected @endif @endisset>8</option>
                            <option value="{{ old('adultos') }}"></option>
                        </select>
                    </div>
                </div>
                <div class="input-field col s6 @if($from === 'resultados') m6 @else m4 @endif">
                    <div ><span class="white-text">Niños(-18)</span></div>
                    <div >
                        <select id="menores" class="browser-default menores form-control" name="menores">
                            <option value="0" selected>0</option>
                            <!-- <option value="" disabled selected>0</option> -->
                            <option value="1" @isset($busqueda) @if($busqueda["menores"] ==1) selected @endif @endisset>1</option>
                            <option value="2" @isset($busqueda) @if($busqueda["menores"] ==2) selected @endif @endisset>2</option>
                            <option value="3" @isset($busqueda) @if($busqueda["menores"] ==3) selected @endif @endisset>3</option>
                            <option value="4" @isset($busqueda) @if($busqueda["menores"] ==4) selected @endif @endisset>4</option>
                            <option value="5" @isset($busqueda) @if($busqueda["menores"] ==5) selected @endif @endisset>5</option>
                            <option value="6" @isset($busqueda) @if($busqueda["menores"] ==6) selected @endif @endisset>6</option>
                        </select>

                    </div>
                </div>
                <div class="input-field col s12">
                    <div class="edadesMenores"></div>
                    <div ></div>
                </div>
                <div class="col s12 @if($from === 'resultados') m12 @else m8 @endif">
                    <div ><span for="checkIn" class="white-text active">Fecha inicio</span></div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons prefix teal-text">today</i></div>
                            <input id="checkIn" type="text" placeholder="DD-MM-YYYY" class="browser-default form-control fechaSalida" name="checkIn" autocomplete="off" required @isset($busqueda) value="{{ $busqueda["checkIn"] }}" @endisset @empty($busqueda) value="" @endempty >
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
    <div class="center">
    <button class="waves-effect waves-light btn has-spinner" onclick="javascript:Busqueda2($(this));">buscar</button>
    {{-- <button class="waves-effect waves-light btn " id="buscar">buscar</button> --}}
    {{-- <button class="waves-effect waves-light btn buscar" >buscar</button> --}}
</div> 
</div>