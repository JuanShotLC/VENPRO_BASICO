import {Component} from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams, ItemSliding} from 'ionic-angular';
import {DetailPage} from "../detail/detail";
import {CalendarPage} from "../calendar/calendar";
import {RegistoPage} from "../registo/registo";
import {MessajePage} from "../messaje/messaje";
import {PerfilPage} from "../perfil/perfil";
import {PerfilformPage} from "../perfilform/perfilform";
import {MiseventosPage} from "../miseventos/miseventos";
import {MyshoppingPage} from "../myshopping/myshopping";
import {MyservicePage} from "../myservice/myservice";
import {ReservaservicePage} from "../reservaservice/reservaservice";
import {OperationsPage} from "../operations/operations";
import {CanjePage} from "../canje/canje";
import {ApiProvider} from "../../providers/api/api";
import {CookieService} from "angular2-cookie/core";
import {NativePageTransitions, NativeTransitionOptions} from "@ionic-native/native-page-transitions";
import {Storage} from '@ionic/storage';
import {SliderPage} from "../slider/slider";
import { Geolocation } from '@ionic-native/geolocation';
import {DetailreservaPage} from "../detailreserva/detailreserva";
import {MisventasPage} from "../misventas/misventas";
import {FCM} from "@ionic-native/fcm";

/**
 * Generated class for the EventosPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
    selector: 'page-eventos',
    templateUrl: 'eventos.html',
})
export class EventosPage {
    myParam: any;
    eventoslist = {};
    eventoslist2: any;
    session_data: any;
    token: any;
    keys: any;
    month: any;
    latitude: any;
    longitude:any;
    contador:any;
    contador2:any;
    url:any;
    categoriaslist:any;
    categoriaselect:any =  null;
    coordenadas = {latitude: null, longitude: null};
    inputseract: any;
    constructor(private geolocation: Geolocation, public navCtrl: NavController,
                private storage: Storage, public navParams: NavParams, public modalCtrl: ModalController,
                public _cookieService: CookieService, public api: ApiProvider,
                private nativePageTransitions: NativePageTransitions,private fcm: FCM) {

      this.storage.get('session').then(session => {
        this.session_data = JSON.parse(session);
        if(this.session_data.id){
          this.fcm.subscribeToTopic('user_'+this.session_data.id)
        }

        this.token = this.session_data.token;
        console.log(session)
      });

      console.log("llego aqui de nuevo");
      this.url= this.api.api_url;
      this.storage.set('status', true);
      this.contador=0;
      this.geolocation.getCurrentPosition().then((resp) => {
        this.coordenadas.latitude =  resp.coords.latitude;
        this.coordenadas.longitude = resp.coords.longitude;
        if(resp){
          this.loadData();
        }
      }).catch((error) => {
        console.log('Error getting location', error);
      });

     /* let watch = this.geolocation.watchPosition();
      watch.subscribe((data) => {
        // data can be a set of coordinates, or an error (if an error occurred).
        this.coordenadas.latitude =  data.coords.latitude
        this.coordenadas.longitude = data.coords.longitude
      });*/


    }

  actualizarcoor(){
      console.log('acciono coordenadas');
    this.geolocation.getCurrentPosition().then((resp) => {
      this.coordenadas.latitude =resp.coords.latitude;
      this.coordenadas.longitude=resp.coords.longitude;
      if(resp){
        this.loadData();
      }
    }).catch((error) => {
      console.log('Error getting location', error);
    });
  }

    loadData() {
      this.api.postinfo('/api/getcoordenadas',this.coordenadas).then((res: any) => {
        this.contador =  res.contada;
        this.eventoslist = res;

      });

      this.api.getinfo('/api/getdataventas/'+this.session_data.id,{id:this.session_data.id}, null).then((res: any) => {
        this.eventoslist2 = res;
          this.contador2 =  this.eventoslist2.length;
      });

      this.api.getinfo('api/listarcate',null, null).then((res: any) => {
          this.categoriaslist = res.categorias;
      });


    }
    goperfil() {
        let options: NativeTransitionOptions = {
            direction: 'right',
            duration: 500,
            slowdownfactor: 3,
            slidePixels: 20,
            iosdelay: 100,
            androiddelay: 150,
            fixedPixelsTop: 0,
            fixedPixelsBottom: 60
        };
        this.nativePageTransitions.slide(options);
        this.navCtrl.push(PerfilPage);
        /*this.navCtrl.setRoot(PerfilPage);*/
    }
    gomyservice() {
        let options: NativeTransitionOptions = {
            direction: 'right',
            duration: 500,
            slowdownfactor: 3,
            slidePixels: 20,
            iosdelay: 100,
            androiddelay: 150,
            fixedPixelsTop: 0,
            fixedPixelsBottom: 60
        };
        this.nativePageTransitions.slide(options);
        this.navCtrl.push(MisventasPage);
    }
    gocompras() {
        this.navCtrl.setRoot(CanjePage);
    }
    godetail(id){
      this.navCtrl.setRoot(DetailreservaPage, {id:id});

    }
    buscarcate(cate){

     console.log(this.categoriaselect);

      this.api.postinfo('/api/categoriamovil/'+ this.categoriaselect,this.coordenadas).then((res: any) => {
        if (res) {
          this.contador =  res.contada;
          this.eventoslist = res;
        }
      });
    }
    buscarseart(inputseract){

        console.log(this.inputseract);

        this.api.postinfo('api/buscarm/'+ this.inputseract,this.coordenadas).then((res: any) => {
          if (res) {
            this.contador =  res.contada;
            this.eventoslist = res;
          }
        });
      }



    okText(){
      console.log("llegue al buscar");
    }
}
