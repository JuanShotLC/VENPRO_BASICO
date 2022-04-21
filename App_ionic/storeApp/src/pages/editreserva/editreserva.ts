import { Component } from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams, ToastController} from 'ionic-angular';
import {ApiProvider} from "../../providers/api/api";
import {CookieService} from "angular2-cookie/core";
import {IMyDrpOptions} from "mydaterangepicker";
import {MyservicePage} from "../myservice/myservice";
import {AlertPage} from "../alert/alert";
import {PerfilPage} from "../perfil/perfil";

/**
 * Generated class for the EditreservaPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-editreserva',
  templateUrl: 'editreserva.html',
})
export class EditreservaPage {

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
  private model: any;
  inicio:any;
  final:any;
  dia:any;
  mes:any;
  hora:any;
  year:any;
  date:any;

  myDateRangePickerOptions: IMyDrpOptions = {};

  constructor(public navCtrl: NavController,public modalCtrl: ModalController, public navParams: NavParams,public _cookieService:CookieService,public api:ApiProvider, public toastCtrl: ToastController) {
    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.token=this.session_data.token;
    this.navParams.get('codigo');
    this.hora=this.navParams.get('hour');
    this.date=this.navParams.get('date');



    this.api.getinfo('/service/'+this.navParams.get('id')+'/' ,{token:this.token}).then((res: any) => {
      this.reservares = res;
      this.titulo= this.reservares.data.title;
      this.description = this.reservares.data.info;
      this.valor = this.reservares.data.value;
      this.calendar = this.reservares.data.calendar;


      this.date = this.date_form(this.date);

      this.dia=this.date.day;
      this.mes=this.date.month;
      this.year=this.date.year;

      if(this.mes<10 ){
        this.dia=this.year+'-0'+this.mes+'-'+this.dia;
      }else{
        this.dia=this.year+'-'+this.mes+'-'+this.dia;
      }
      this.mes=this.dia;


      console.log(this.mes);
      console.log(this.dia);


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
      'code':""+this.navParams.get('codigo'),
      'date':""+this.year+this.mes+this.dia,
      'hour':""+this.hora
    }
    console.log(qq);




    this.api.putinfo('/service/'+this.navParams.get('id')+'/?token='+this.session_data.token,qq).then((res: any) => {

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
        this.myParam.messaje = 'LA RESERVA HA SIDO EDITADA.';
        this.myParam.fontipe = 2;
        let myModal = this.modalCtrl.create(AlertPage, {'myParam': this.myParam});
        myModal.present();
      }

    });

  }
  atras() {
    this.navCtrl.setRoot(PerfilPage);
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
