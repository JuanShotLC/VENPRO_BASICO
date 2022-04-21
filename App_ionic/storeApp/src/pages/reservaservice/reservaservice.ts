import { Component } from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams, ToastController} from 'ionic-angular';
import {MyservicePage} from "../myservice/myservice";
import {AlertPage} from "../alert/alert";
import {CookieService} from "angular2-cookie/core";
import {ApiProvider} from "../../providers/api/api";
import {IMyDrpOptions} from 'mydaterangepicker';

/**
 * Generated class for the ReservaservicePage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-reservaservice',
  templateUrl: 'reservaservice.html',
})
export class ReservaservicePage {
  myParam = {valor:null,messaje:null,fontipe:null,volver :null};




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
  mes:any;
  hora:any;
  year:any;


  private model: any;

  myDateRangePickerOptions: IMyDrpOptions = {};

  constructor(public navCtrl: NavController, public navParams: NavParams,public modalCtrl: ModalController,public _cookieService:CookieService,public api:ApiProvider, public toastCtrl: ToastController) {

    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.token=this.session_data.token;

    let dt = new Date();
    let h = dt.getHours()
    this.mes = dt.getMonth()+1;
    let day = dt.getDate();
    let year = dt.getFullYear();



    this.hora='01:00';
    if(this.mes<10 ){
      this.dia=year+'-0'+this.mes+'-'+day;
    }else{
      this.dia=year+'-'+this.mes+'-'+day;
    }

    if(this.mes<10){
      this.mes=year+'-0'+this.mes+'-'+day;
    }else{
      this.mes=year+'-'+this.mes+'-'+day;

    }
  console.log(this.dia);




    this.api.getinfo('/service/'+this.navParams.get('id')+'/' ,{token:this.token}).then((res: any) => {
      this.reservares = res;
      this.titulo= this.reservares.data.title;
      this.description = this.reservares.data.info;
      this.valor = this.reservares.data.value;
      this.calendar = this.reservares.data.calendar;




    });

  }

  date_form(data_string:string){
    let year = data_string.substring(0,4);
    let month = data_string.substring(4,6);
    let day = data_string.substring(6,8);

    return {year: parseInt(year), month: parseInt(month), day: parseInt(day)}
  }

  openModalWithParams() {



    let day = new Date(this.dia);
    this.dia= day.getDate()+1;
    this.year = day.getFullYear();

    let month = new Date(this.mes);
    this.mes = month.getMonth()+1;




    console.log(this.dia);
    console.log(this.mes);
    console.log(this.hora);
    console.log(this.year);
    if(this.mes<10){
      this.mes='0'+this.mes;
    }

    let qq = {
      'date':""+this.year+this.mes+this.dia,
      'hour':""+this.hora
    }
    console.log(qq);




    this.api.postinfo('/service/'+this.navParams.get('id')+'/?token='+this.session_data.token,qq).then((res: any) => {

      console.log(res.status);
      if (res.status == 403) {
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
        this.myParam.valor = status;
        this.myParam.volver = 'service';
        this.myParam.messaje = 'EL ' + this.titulo + ' HA SIDO RESERVADO.';
        this.myParam.fontipe = 2;
        let myModal = this.modalCtrl.create(AlertPage, {'myParam': this.myParam});
        myModal.present();
      }

    });

  }
  atras() {
    this.navCtrl.setRoot(MyservicePage);
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
