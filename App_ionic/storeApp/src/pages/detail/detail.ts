import { Component } from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams, ToastController} from 'ionic-angular';
import {EventosPage} from "../eventos/eventos";
import {AlertPage} from "../alert/alert";
import {ApiProvider} from "../../providers/api/api";
import {CookieService} from "angular2-cookie/core";
import {MomentModule} from 'angular2-moment/moment.module';

/**
 * Generated class for the DetailPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-detail',
  templateUrl: 'detail.html',
})
export class DetailPage {
  myParam = {valor:null,messaje:null,fontipe:null};
  eventores ={};
  session_data:any;
  token:any;
  fecha:any;
  lastUpdated:any;
  existe:any=false;
  min:number=1;
  cantidad:number=1;
  type:any;
  constructor(public navCtrl: NavController, public navParams: NavParams,public modalCtrl: ModalController,public _cookieService:CookieService,public api:ApiProvider, public toastCtrl: ToastController) {

    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.token=this.session_data.token;
    this.type=this.navParams.get('type');
    console.log(this.type);

    this.api.getinfo('/event/'+this.navParams.get('id')+'/' ,{token:this.token}).then((res: any) => {
      this.eventores = res;

      if (this.type=='event') {
        let someArray = res.users;
        console.log(someArray);

        if (res.users) {
          someArray.forEach(element => {
            console.log(element.ID);
            if (element.ID== this.session_data.ID) {
              this.existe = true;
            }
          })
/*          for (let entry in someArray) {
            let v = someArray[entry][0].ID;
            console.log(v);
            if (v == this.session_data.ID) {
              this.existe = true;
            }
          }*/
        }
      }


      if(this.type=='event'){
        this.fecha=res.date_event;

        let year = this.fecha.substring(0,4);
        let month = this.fecha.substring(4,6);
        let day = this.fecha.substring(6,8);

        let  fechahoy = new Date(year-1 , month-1 , day);


        this.lastUpdated = new Date(year,month-1,day);

        console.log(fechahoy);
      }

    });


  }

  up() {
    this.cantidad=this.cantidad+1;
  }
  dow() {
    if(this.cantidad>1){
      this.cantidad=this.cantidad-1;
    }

  }

  //asistir a un evento
  asistir(valor) {

    console.log(this.cantidad);

    this.api.postinfo('/event/'+this.navParams.get('id')+'/user/?token='+this.token ,{qty:this.cantidad}).then((res: any) => {
      console.log(res);
      if(res.status==401){
        this.presentToast("El usuario existe en el evento");
      }else{
        let status=valor;
        this.myParam.valor=status;
        this.myParam.messaje='HAS SIDO REGISTRADO EXITOSAMENTE :)';
        this.myParam.fontipe=2;
        let myModal = this.modalCtrl.create(AlertPage, { 'myParam': this.myParam });
        this.existe=true;
        myModal.present();
      }
    });
  }
  //dejar de asistir a un evento
  noasitir(valor) {
    this.api.deleteinfo('/event/'+this.navParams.get('id')+'/user/' ,{token:this.token}).then((res: any) => {
      console.log(res);
      if(res.status==401){
        this.presentToast("Ya paso la fecha limite para cancelar");
      }if(res.status_code==200){
        let status=valor;
        this.myParam.valor=status;
        this.myParam.messaje='TU RESERVACIÓN HA SIDO CANCELADA';
        this.myParam.fontipe=2;
        this.existe=false;
        let myModal = this.modalCtrl.create(AlertPage, { 'myParam': this.myParam });
        myModal.present();
      }
    });
  }

  godlist() {
    this.navCtrl.setRoot(EventosPage);
  }

  presentToast(msg) {
    let toast = this.toastCtrl.create({
      message: msg,
      duration: 3000,
      position: 'bottom'
    });

    toast.onDidDismiss(() => {
      console.log('Dismissed toast');
    });

    toast.present();
  }

}
