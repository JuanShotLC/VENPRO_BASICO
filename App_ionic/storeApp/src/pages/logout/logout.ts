import { Component } from '@angular/core';
import { MenuController, NavController, NavParams } from 'ionic-angular';
import { ApiProvider } from '../../providers/api/api';
import { LoginPage } from '../login/login';
import { CookieService } from 'angular2-cookie/core';
import {Storage} from '@ionic/storage';
/**
 * Generated class for the LogoutPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */

@Component({
  selector: 'page-logout',
  templateUrl: 'logout.html',
  providers:[ApiProvider]
})
export class LogoutPage {

  constructor(private storage: Storage,public _cookieService:CookieService,private menu: MenuController,public api:ApiProvider,public navCtrl: NavController, public navParams: NavParams) {
  	this.menu.swipeEnable(false, 'menu-main');
    this.api.logout().then((res:any)=>{
      console.log(res)
      if(res.status=='success')
        this._cookieService.put('session',null);
        this.storage.set('status', false);
        this.navCtrl.setRoot(LoginPage);
    });
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad LogoutPage');
  }

}
