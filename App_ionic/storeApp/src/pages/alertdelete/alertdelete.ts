import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import {PerfilPage} from "../perfil/perfil";
import {Storage} from "@ionic/storage";
import {EventosPage} from "../eventos/eventos";
import {LoginPage} from "../login/login";

/**
 * Generated class for the AlertdeletePage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-alertdelete',
  templateUrl: 'alertdelete.html',
})
export class AlertdeletePage {

  constructor(public navCtrl: NavController,private storage: Storage, public navParams: NavParams) {
  }

  cancel() {
    this.navCtrl.setRoot(PerfilPage);
  }

  logout(){
      this.storage.remove('session');
      this.navCtrl.setRoot(LoginPage);
  }

}
