import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";

/**
 * Generated class for the MiseventosPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-miseventos',
  templateUrl: 'miseventos.html',
})
export class MiseventosPage {

  constructor(public navCtrl: NavController, public navParams: NavParams) {
  }

  atras3() {
    this.navCtrl.setRoot(EventosPage);
  }

}
