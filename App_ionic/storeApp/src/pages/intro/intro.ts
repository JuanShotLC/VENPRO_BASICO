import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import {Storage} from '@ionic/storage';
import {EventosPage} from "../eventos/eventos";
import {SliderPage} from "../slider/slider";

/**
 * Generated class for the IntroPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-intro',
  templateUrl: 'intro.html',
})
export class IntroPage {

  constructor(public navCtrl: NavController, public navParams: NavParams,private storage: Storage) {
    this.storage.set('status', true);
  }

  goeventos(){
    this.navCtrl.setRoot(EventosPage);
  }
  next(){
    this.navCtrl.setRoot(SliderPage);
  }

}
