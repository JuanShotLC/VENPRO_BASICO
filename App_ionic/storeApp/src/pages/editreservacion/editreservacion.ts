import { Component } from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams, ToastController} from 'ionic-angular';
import {PerfilPage} from "../perfil/perfil";
import {AlertPage} from "../alert/alert";
import {CookieService} from "angular2-cookie/core";
import {ApiProvider} from "../../providers/api/api";
import {IMyDrpOptions} from "mydaterangepicker";
import {CabanasPage} from "../cabanas/cabanas";
import {MyreservasPage} from "../myreservas/myreservas";

/**
 * Generated class for the EditreservacionPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-editreservacion',
  templateUrl: 'editreservacion.html',
})
export class EditreservacionPage {

  myParam = {valor:null,messaje:null,fontipe:null};




  // For example initialize to specific date (09.10.2018 - 19.10.2018). It is also possible
  // to set initial date range value using the selDateRange attribute.

  servicelist ={};
  date_begin:any;
  date_end:any;
  session_data:any;
  token:any;
  reservares:any;
  titulo:any;
  description:any;
  valor:any;
  calendar:any;
  entra:any;
  dia:any;
  private model: any;
  inicio:any;
  final:any;
  tipo:any;
  tipos:any;
  myDateRangePickerOptions: IMyDrpOptions = {};

  constructor(public navCtrl: NavController,public modalCtrl: ModalController, public navParams: NavParams,public _cookieService:CookieService,public api:ApiProvider, public toastCtrl: ToastController) {
    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.token=this.session_data.token;

    this.titulo=this.navParams.get('title');
    this.tipos=this.navParams.get('tipo');

    this.inicio = this.date_form(this.navParams.get('init'));


    this.final = this.date_form(this.navParams.get('end'));

    this.model = {beginDate:  this.inicio,
      endDate:  this.final};


    console.log(this.model);


    this.myDateRangePickerOptions = {
      dateFormat: 'dd.mm.yyyy',
      inline:true,
      showSelectDateText:true,
      showClearBtn:false,
      showApplyBtn:false,
      yearSelector:false,
      /* disableSince:  this.date_end,*!/*/
      markCurrentMonth : false

    }

  }
  date_form(data_string:string){
    let year = data_string.substring(0,4);
    let month = data_string.substring(4,6);
    let day = data_string.substring(6,8);

    return {year: parseInt(year), month: parseInt(month), day: parseInt(day)}
  }

  tipof(tipo:string){

    this.tipo=tipo;
    console.log(this.tipo);
  }

  /* date_form(data_string:string){
     let year = data_string.substring(0,4);
     let month = data_string.substring(4,7);
     let day = data_string.substring(7,9);

     return {year: parseInt(year), month: parseInt(month), day: parseInt(day)}
   }*/

  openModalWithParams(valor) {

    console.log(this.model);
    let dato1=this.model.beginDate;
    let mes1=dato1.month;
    if(mes1<10){
      mes1='0'+dato1.month;
    }

    let dato2=this.model.endDate;
    let mes2=dato2.month;
    if(mes2<10){
      mes2='0'+dato2.month;
    }
    let qq = {
      'code':this.navParams.get('codigo'),
      'init':""+dato1.year+mes1+dato1.day,
      'end':""+dato2.year+mes2+dato2.day
    }

    console.log(qq);
    console.log(this.tipo);


    this.api.putinfo('/booking/reservation/'+this.navParams.get('id')+'/?token='+this.session_data.token,qq).then((res: any) => {

      if(res.data.success==true){
        this.presentToast("la reserva ha sido editada");
      }else{
        this.presentToast("No se pudo editar la reserva");
      }


      /* this.navCtrl.setRoot(CabanasPage);*/
      /* if (res.status == 403) {
         this.presentToast("El día de inicio debe ser más alto que hoy");
       }
       if (res.status == 404) {
         this.presentToast("No hay disponibilidad para este servicio");
       }
       if (res.status == 400) {
         this.presentToast("la fecha de finalización debe ser mayor que la inicial.");
       }
       if (res.status == 401) {
         this.presentToast("Ya hay una reserva para este día");
       }
       if (res.status == 500) {
         this.presentToast("la fecha de finalización debe ser mayor que la inicial");
       }

       if (res.status_code == 200) {
         let status = valor;
         this.myParam.valor = status;
         this.myParam.messaje = 'EL ' + this.titulo + ' HA SIDO AGREGADO A TU LISTA.';
         this.myParam.fontipe = 2;
         let myModal = this.modalCtrl.create(AlertPage, {'myParam': this.myParam});
         myModal.present();
       }*/

    });

  }
  atras() {
    this.navCtrl.setRoot(MyreservasPage);
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
