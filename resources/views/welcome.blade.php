@extends('layouts.app')

@section('content')
<div id="index-banner" class="parallax-container">
  <div class="section no-pad-bot">
    <div class="container">
      <br><br>
      <h4 class="header center text-lighten-2">Kenya's preferred property market</h4>
      <div class="row center">
        <div class="row">
          <div class="col s12">
            <ul class="tabs main-color">
              <li class="tab col s3"><a href="#test1" class="active white-text">House</a></li>
              <li class="tab col s3"><a  href="#test2" class="white-text">Land</a></li>
            </ul>
          </div>
          <div id="test1" class="col s12">
            <div class="card horizontal">
              <div class="card-stacked">
                <div class="card-content z-depth-2 teal-text " >
                  <form class=" " role="form" method="POST" action="{{ url('/house-homepage-search') }}">
                    {{ csrf_field() }}

                    <div class=" form-group  {{ $errors->has('location') ? ' has-error' : '' }}">

                      <div class="input-field col m12 s12">
                        <input id="location" type="text" class="form-control" name="location" value="{{ old('location') }}"  autofocus>
                        <label for="location" class="">Location</label>
                        @if ($errors->has('location'))
                        <span class="help-block">
                          <strong>{{ $errors->first('location') }}</strong>
                        </span>
                        @endif
                      </div>
                    </div>
                    <div class=" form-group">
                      <div class=" col m6 s6">
                        <select >
                          <option value="" disabled selected>House type</option>
                          <option value="Apartment/Flat">Apartment / Flat</option> 
                          <option value="Bedsitter">Bedsitter</option>
                          <option value="Guest House">Guest House</option>
                          <option value="House">House</option>
                          <option value="Office/Commercial">Office / Commercial</option>
                          <option value="Stall">Stall</option>
                          <option value="Warehouse/Godown">Warehouse / Godown</option>
                        </select>
                      </div>
                    </div>
                    <div class=" form-group">
                      <div class="col m6 s6">
                        <select >
                          <option value="" disabled selected>Buy/ Rent</option>
                          <option value="Apartment/Flat">Any</option> 
                          <option value="Sale">Buy</option>
                          <option value="Rent">Rent</option>
                        </select>
                      </div>
                    </div>
                    <div class=" form-group  {{ $errors->has('minimum') ? ' has-error' : '' }}">

                      <div class="input-field col m6 s6">
                        <input id="minimum" type="text" class="form-control" name="minimum" value="{{ old('location') }}"  autofocus>
                        <label for="minimum" class="">Min Price</label>
                        @if ($errors->has('minimum'))
                        <span class="help-block">
                          <strong>{{ $errors->first('minimum') }}</strong>
                        </span>
                        @endif
                      </div>
                    </div>
                    <div class=" form-group  {{ $errors->has('maximum') ? ' has-error' : '' }}">

                      <div class="input-field col m6 s6">
                        <input id="maximum" type="text" class="form-control" name="maximum" value="{{ old('maximum') }}"  autofocus>
                        <label for="maximum" class="">Max Price</label>
                        @if ($errors->has('maximum'))
                        <span class="help-block">
                          <strong>{{ $errors->first('maximum') }}</strong>
                        </span>
                        @endif
                      </div>
                    </div>

                    <div class="col m6 s6">
                     <select >
                      <option value="" disabled selected>Bedrooms</option>
                      <option value="Apartment/Flat">Apartment / Flat</option> 
                      <option value="">Any</option>
                      <option value="1">1+</option>
                      <option value="2">2+</option>
                      <option value="3">3+</option>
                      <option value="4">4+</option>
                      <option value="5"> 5+ </option>
                    </select>
                  </div>
                  <div class="col m6 s6">
                   <select >
                    <option value="" disabled selected>Bathrooms</option>
                    <option value="Apartment/Flat">Apartment / Flat</option> 
                    <option value="">Any</option>
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="3">3+</option>
                    <option value="4">4+</option>
                    <option value="5"> 5+ </option>
                  </select>
                </div>

                <div class="col m12 s12">
                 <div class="form-group">
                  <div class="col m12 s12">
                    <button type="submit" class="home_searchbutton waves-effect waves-light btn main-color" >
                      Search
                    </button>                    
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div id="test2" class="col s12"><div class="card horizontal">
      <div class="card-stacked">
        <div class="card-content z-depth-2  teal-text" >
         <form class=" " role="form" method="POST" action="{{ url('/land-homepage-search') }}">
          {{ csrf_field() }}

          <div class=" form-group  {{ $errors->has('location') ? ' has-error' : '' }}">

            <div class="input-field col m12 s12">
              <input id="location" type="text" class="form-control" name="location" value="{{ old('location') }}"  autofocus>
              <label for="location" class="">Location</label>
              @if ($errors->has('location'))
              <span class="help-block">
                <strong>{{ $errors->first('location') }}</strong>
              </span>
              @endif
            </div>
          </div>
          <div class=" form-group">
            <div class=" col m6 s6">
              <select >
                <option value="" disabled selected>Acres</option>
                <option value="0,00.25">0 acre - 1/4 acre</option>
                <option value="0.25,0.50">1/4 acre - 1/2 acre</option>
                <option value="0.50,1.00">1/2 acre - 1 acre</option>
                <option value="1.00,3.00">1 acres - 3 acres</option>
                <option value="3.00,5.00">3 acres - 5 acres</option>
                <option value="5.00,10.00">5 acres - 10 acres</option>
                <option value="10.00,15.00">10 acres - 15 acres</option>
                <option value="15.00,20.00">15 acres - 20 acres</option>
                <option value="20.00,30.00">20 acres - 30 acres</option>
                <option value="30.00,50.00">30 acres - 50 acres</option>
                <option value="50.00,">Over 50 acres</option>
              </select>
            </div>
          </div>
          <div class=" form-group">
            <div class="col m6 s6">
              <select >
                <option value="" disabled selected>Buy/ Rent</option>
                <option value="Apartment/Flat">Any</option> 
                <option value="Sale">Buy</option>
                <option value="Rent">Rent</option>
              </select>
            </div>
          </div>
          <div class=" form-group  {{ $errors->has('minimum') ? ' has-error' : '' }}">

            <div class="input-field col m6 s6">
              <input id="minimum" type="text" class="form-control" name="minimum" value="{{ old('location') }}"  autofocus>
              <label for="minimum" class="">Min Price</label>
              @if ($errors->has('minimum'))
              <span class="help-block">
                <strong>{{ $errors->first('minimum') }}</strong>
              </span>
              @endif
            </div>
          </div>
          <div class=" form-group  {{ $errors->has('maximum') ? ' has-error' : '' }}">

            <div class="input-field col m6 s6">
              <input id="maximum" type="text" class="form-control" name="maximum" value="{{ old('maximum') }}"  autofocus>
              <label for="maximum" class="">Max Price</label>
              @if ($errors->has('maximum'))
              <span class="help-block">
                <strong>{{ $errors->first('maximum') }}</strong>
              </span>
              @endif
            </div>
          </div>
          <div class="col m12 s12">
           <div class="form-group">
            <div class="col ">
              <button type="submit" class="home_searchbutton2 waves-effect waves-light btn main-color" >
                Search
              </button>                     
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
</div>
<div class="row center">
</div>
<br><br>

</div>
</div>

<div class="parallax"><img src="http://localhost:8000/assets/images/1.jpg" alt="Unsplashed background img 1"></div>
</div>


  <div class="row">
    <!-- Start of -->
    <div class="col m12 offset-m0">


      <!-- Coursel -->
      <div class="row featured-row">
      <span class="pull-left featured-line">FEATURED PROPERTIES</span>
      <div class=" pull-right">
        <a class="w3-btn-floating" onclick="plusDivs(-1)">&#10094;</a>
        <a class="w3-btn-floating" onclick="plusDivs(+1)">&#10095;</a>
      </div>
      </div>
      <div>

       @foreach ( $properties as $property )
          
            <div class="col s12 m12">
             <div class="w3-display-container mySlides">
              @if( $property->picture_url !="" )
              <a href="/property/property-details/{{ encrypt($property->property_id) }}"><img src="uploads/{{ $property->picture_url }}" alt="..." class="home-click-link  img-responsive img-rounded" style="width:100%"></a>
              @else
              <a href="/property/property-details/{{ encrypt($property->property_id) }}"><img src="uploads/4.jpg" alt="..." class="home-click-link img-responsive img-rounded" style="width:100%"></a>
              @endif
             <div class="w3-display-bottomleft w3-container w3-padding-16 home_caption">
                  <a href="/property/property-details/{{ encrypt($property->property_id) }}"><span class="home-click-link"> <h6 style="border-top:2px solid white; border-bottom:2px solid white; padding:10px;">{{ $property->category }} for {{ $property->sale_rent }}, {{ $property->county_name }} ,  {{ $property->sub_county }}</h6></a>
                   
                    
                   @if( $property->category == 'House')
                   <span> <span class="prop_price"> {{ $property->price }} KSH</span> <i class="fa fa-bed "> </i>&nbsp;&nbsp; {{ $property->bedroom }}</span> <span><i class="fa fa-asterisk "> </i>&nbsp;&nbsp;   {{ $property->bathroom }}</span>
                   @if( $property->property_plan == 'Featured')
                   <span class="property-plan pull-right"></span>
                   @endif
                   @else
                   <span><span class="prop_price"> {{ $property->price }} KSH</span> {{ $property->acres }} Acres</span>
                   @endif
                                                      
                 <span class=""> <i class="fa fa-thumbs-o-up text-primary myfavourite" id="{{ $property->property_id }}"></i>&nbsp;&nbsp;<a href="#"><span class="badge updated_likes{{ $property->property_id }}" >{{ $property->user_likes }}</span></a></span>
        </div>
        </div>
        </div>
       @endforeach

    </div>
  </div>
  <!-- End of post form-->

</div>
@endsection
