<div>
    <div class="row">
        <h6 class="fs-12 mt-4 mb-5">{{ __('Following information will be sent directly to payment provider') }}</h6>
        <div class="col-md-6 col-sm-12">
            <div class="input-box">								
                <h6>{{__('First Name')}} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                <div class="form-group">							    
                    <input type="text" class="form-control @error('name') is-danger @enderror" id="name" name="name" autocomplete="off">
                </div>
                    @error('name')
                    <p class="text-danger">{{ $errors->first('name') }}</p>
                @enderror
            </div> 
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-box">								
                <h6>{{__('Last Name')}} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                <div class="form-group">							    
                    <input type="text" class="form-control @error('lastname') is-danger @enderror" id="lastname" name="lastname" autocomplete="off">
                </div>
                    @error('lastname')
                    <p class="text-danger">{{ $errors->first('lastname') }}</p>
                @enderror
            </div> 
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-box">								
                <h6>{{__('Identity Number')}} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                <div class="form-group">							    
                    <input type="text" class="form-control @error('identity') is-danger @enderror" id="identity" name="identity" autocomplete="off">
                </div>
                    @error('identity')
                    <p class="text-danger">{{ $errors->first('identity') }}</p>
                @enderror
            </div> 
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-box">								
                <h6>{{__('Mobile Number')}} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                <div class="form-group">							    
                    <input type="text" class="form-control @error('phone') is-danger @enderror" id="phone" name="phone" autocomplete="off">
                </div>
                    @error('phone')
                    <p class="text-danger">{{ $errors->first('phone') }}</p>
                @enderror
            </div> 
        </div>
        <div class="col-sm-12">
            <div class="input-box">								
                <h6>{{__('Email Address')}} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                <div class="form-group">							    
                    <input type="email" class="form-control @error('email') is-danger @enderror" id="email" name="email" autocomplete="off">
                </div>
                    @error('email')
                    <p class="text-danger">{{ $errors->first('email') }}</p>
                @enderror
            </div> 
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="input-box">								
                <h6>{{__('Billing Address')}} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                <div class="form-group">							    
                    <input type="text" class="form-control @error('address') is-danger @enderror" id="address" name="address" autocomplete="off">
                </div>
                    @error('address')
                    <p class="text-danger">{{ $errors->first('address') }}</p>
                @enderror
            </div> 
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="input-box">								
                <h6>{{__('City')}} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                <div class="form-group">							    
                    <input type="text" class="form-control @error('city') is-danger @enderror" id="city" name="city" autocomplete="off">
                </div>
                    @error('city')
                    <p class="text-danger">{{ $errors->first('city') }}</p>
                @enderror
            </div> 
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="input-box">								
                <h6>{{__('Zip Code')}} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                <div class="form-group">							    
                    <input type="text" class="form-control @error('zipcode') is-danger @enderror" id="zipcode" name="zipcode" autocomplete="off">
                </div>
                    @error('zipcode')
                    <p class="text-danger">{{ $errors->first('zipcode') }}</p>
                @enderror
            </div> 
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="input-box">								
                <h6>{{__('Country')}} <span class="text-required"><i class="fa-solid fa-asterisk"></i></span></h6>
                <div class="form-group">							    
                    <input type="text" class="form-control @error('country') is-danger @enderror" id="country" name="country" autocomplete="off">
                </div>
                    @error('country')
                    <p class="text-danger">{{ $errors->first('country') }}</p>
                @enderror
            </div> 
        </div>
    </div>
    
    
       
    
    
</div>