import {Component} from '@angular/core';
import {MenuController, NavController, NavParams, AlertController} from 'ionic-angular';
import {ApiProvider} from '../../providers/api/api';
import {CookieService} from 'angular2-cookie/core';
import {Storage} from '@ionic/storage';
import {RecuperaPage} from "../recupera/recupera";
import {SliderPage} from "../slider/slider";
import {IntroPage} from "../intro/intro";
import {EventosPage} from "../eventos/eventos";
import {RegisteruserPage} from "../registeruser/registeruser";

/**
 * Generated class for the LoginPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */

@Component({
    selector: 'page-login',
    templateUrl: 'login.html',
    providers: [ApiProvider, CookieService]
})
export class LoginPage {
    registerCredentials = {email: null, pass: null, token_device: null};
    session_data: any;
    status: any;
    logout: any;
    token: any;

    constructor(private alertCtrl: AlertController, private storage: Storage, public _cookieService: CookieService, private menu: MenuController, public api: ApiProvider, public navCtrl: NavController, public navParams: NavParams) {
        this.menu.swipeEnable(false, 'menu-main');
        this.logout = this.navParams.get('status');

        this.storage.get('session').then(session => {
            if(session){
                this.session_data = JSON.parse(session);
                this._cookieService.put('session', session);
                this.token = this.session_data.token;
                this.navCtrl.setRoot(EventosPage);
            }
        });

    }

    login() {

        this.registerCredentials.token_device = this._cookieService.get('token_device');
        let data_req: any = this.registerCredentials;
        this.api.login(data_req).then((res) => {

            if (res.status == "success")
                this.navCtrl.setRoot(SliderPage);
            this._cookieService.put('session', JSON.stringify(res.data));
            this.storage.set('session', JSON.stringify(res.data));
        });

    }

    forgotPass() {
        this.navCtrl.setRoot(RecuperaPage);
    }
    Registerp() {
      this.navCtrl.setRoot(RegisteruserPage);
    }




}
