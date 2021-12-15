        <form 
            action="{{url('admin/settings/update')}}" 
            method="post" 
            class="validate-form ajax-submit" 
            enctype='multipart/form-data'
        >
        {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Correo de notificación</label>
                        <input type="text" class="form-control" name="settings[emailNotifications]" value="{{$settings['emailNotifications']}}">
                    </div>
                    <div class="form-group">
                        <label>Telefono reservas y contacto</label>
                        <input type="text" class="form-control" name="settings[contactPhone]" value="{{$settings['contactPhone']}}">
                    </div>
                    <div class="form-group">
                        <label>Facebook</label>
                        <input type="text" class="form-control" name="settings[facebookLink]" value="{{$settings['facebookLink']}}">
                    </div>
                    <div class="form-group">
                        <label>Twitter</label>
                        <input type="text" class="form-control" name="settings[twitterLink]" value="{{$settings['twitterLink']}}">
                    </div>
                    <div class="form-group">
                        <label>Google+</label>
                        <input type="text" class="form-control" name="settings[googleLink]" value="{{$settings['googleLink']}}">
                    </div>
                    <div class="form-group">
                        <label>Instagram</label>
                        <input type="text" class="form-control" name="settings[instagramLink]" value="{{$settings['instagramLink']}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Color fondo primario</label>
                        <input type="color" class="form-control" name="settings[color1]" value="{{$settings['color1']}}">
                    </div>
                    <div class="form-group">
                        <label>Color de letra primario (para fondo blanco)</label>
                        <input type="color" class="form-control" name="settings[color2]" value="{{$settings['color2']}}">
                    </div>
                    <div class="form-group">
                        <label>Color fondo botones</label>
                        <input type="color" class="form-control" name="settings[color3]" value="{{$settings['color3']}}">
                    </div>
                    <div class="form-group">
                        <label>Color letra botones</label>
                        <input type="color" class="form-control" name="settings[color4]" value="{{$settings['color4']}}">
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class="col-md-6">
                    <div class="group-form text-center">
                        <label>Logo</label><br>
                        <img style="max-width: 100%" src="<?= SettingsComponent::getLogoUrl(); ?>">
                        <br>
                        <br>
                        <input type="file" name="imageFile" accept=".jpg, .png" >
                        <br>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="group-form text-center">
                        <label>Icono</label><br>
                        <img style="max-width: 100%" src="<?= SettingsComponent::getIcoUrl(); ?>">
                        <br>
                        <br>
                        <input type="file" name="icoFile" accept=".jpg, .png, .ico" >
                        <br>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Direccion empresa</label>
                    <textarea class="html-content" name="settings[address]">{{$settings['address']}}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Terminos y condiciones</label>
                    <textarea class="html-content" name="settings[termsText]">{{$settings['termsText']}}</textarea>
                </div>
                <div class="form-group col-md-12">
                    <label>Politica de privacidad</label>
                    <textarea class="html-content" name="settings[privacyText]">{{$settings['privacyText']}}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Politica HOTEL</label>
                    <textarea class="html-content" name="settings[hotelPolicy]">{{$settings['hotelPolicy']}}</textarea>
                </div>
                <div class="form-group col-md-12">
                    <label>Politica tarifa de HOTEL</label>
                    <textarea class="html-content" name="settings[hotelPolicy]">{{$settings['hotelRatePolicy']}}</textarea>
                </div>
                <div class="form-group col-md-12">
                    <label>Politica TOURS</label>
                    <textarea class="html-content" name="settings[toursPolicy]">{{$settings['toursPolicy']}}</textarea>
                </div>
                <div class="form-group col-md-12">
                    <label>Politica TRANSPORTE</label>
                    <textarea class="html-content" name="settings[transferPolicy]">{{$settings['transferPolicy']}}</textarea>
                </div>
            </div>
            <hr>
            <button class="btn btn-primary" type="submit">Guardar</button>
        </form>