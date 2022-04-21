import { Component } from '@angular/core';
import { IonicPage, MenuController, NavController, NavParams} from 'ionic-angular';
import {LoginPage} from "../login/login";
import {CookieService} from "angular2-cookie/core";
import { Storage } from '@ionic/storage';
import {SliderPage} from "../slider/slider";


/**
 * Generated class for the InicioPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-inicio',
  templateUrl: 'inicio.html',
})
export class InicioPage {

  status :any;
  logout :any;

  constructor(public navCtrl: NavController,public navParams: NavParams,private menu: MenuController,public _cookieService:CookieService,private storage:Storage) {
    this.menu.swipeEnable(false, 'menu-main');

    this.logout = this.navParams.get('status');

    if (this.logout == 'false') {
      this._cookieService.removeAll();
    } else {
      this.storage.get('status').then(status => {
        this.status = status;
        console.log(this.status);
        if (this.status == true) {
          this.navCtrl.setRoot(SliderPage);
        }
      });
    }



  }


  loginini(){
    this.navCtrl.setRoot(LoginPage);
  }


}
