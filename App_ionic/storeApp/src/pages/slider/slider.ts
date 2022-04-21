import {Component, ViewChild} from '@angular/core';
import {IonicPage, NavController, NavParams, Slides} from 'ionic-angular';
import {Storage} from '@ionic/storage';
import {EventosPage} from "../eventos/eventos";
import {NativePageTransitions, NativeTransitionOptions} from "@ionic-native/native-page-transitions";
import {RecuperaPage} from "../recupera/recupera";

/**
 * Generated class for the SliderPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-slider',
  templateUrl: 'slider.html',
})
export class SliderPage {
  @ViewChild(Slides) slides: Slides;

  currenindex:any;
  atras:any;
  alante:any;

  constructor(public navCtrl: NavController, public navParams: NavParams,private storage: Storage,private nativePageTransitions: NativePageTransitions) {
    this.storage.set('status', true);
    this.atras=null;
    this.alante=true;

  }

  goToSlide() {
    this.currenindex = this.slides.getActiveIndex();
    console.log(this.atras, this.currenindex);

    if(this.currenindex==0 || this.currenindex==1 || this.currenindex==2 || this.currenindex==3){
      this.atras=true;
    }else{
      this.atras=null;
    }
    if(this.currenindex==2){
      this.alante=null;
    }else{
      this.alante=this.slides.getActiveIndex();
    }
    this.slides.slideNext( 500);
  }
  preSlide() {
    let valor1 = this.slides.isBeginning();
    console.log(valor1);
    this.currenindex = this.slides.getActiveIndex();
    console.log(this.atras, this.currenindex);

    if(this.currenindex==0 || this.currenindex==1 || this.currenindex==2 || this.currenindex==3){
      this.atras=true;
    }else{
      this.atras=null;
    }
    if(this.currenindex==2){
      this.alante=null;
    }else{
      this.alante=this.slides.getActiveIndex();
    }

    this.slides.slidePrev( 500);
  }
  irweb(){
    window.open('https://stuart.com/es/', '_blank');
  }

  ionViewWillLeave() {

    /*let options: NativeTransitionOptions = {
      direction: 'up',
      duration: 500,
      slowdownfactor: 3,
      slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0,
      fixedPixelsBottom: 60
    };

    this.nativePageTransitions.slide(options)
      .then(()=>{
        console.log('exito');
      })
      .catch(()=>{
          console.log('error');
        }
      );*/

  }


  goeventos(){
    /*let options: NativeTransitionOptions = {
      direction: 'up',
      duration: 500,
      slowdownfactor: 3,
      slidePixels: 20,
      iosdelay: 100,
      androiddelay: 150,
      fixedPixelsTop: 0,
      fixedPixelsBottom: 60
    };

    this.nativePageTransitions.slide(options);
    this.navCtrl.push(EventosPage);*/
    console.log('Aqui algo')
    this.navCtrl.push(EventosPage);
  }






}
