import { Component } from '@angular/core';
import {IonicPage, ModalController, NavController, NavParams} from 'ionic-angular';
import {PerfilPage} from "../perfil/perfil";
import {EditreservaPage} from "../editreserva/editreserva";
import {CookieService} from "angular2-cookie/core";
import {ApiProvider} from "../../providers/api/api";
import {CalendarPage} from "../calendar/calendar";
import {AlertPage} from "../alert/alert";
import {DetailreservaPage} from "../detailreserva/detailreserva";

/**
 * Generated class for the CabanasPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-cabanas',
  templateUrl: 'cabanas.html',
})
export class CabanasPage {
    myParam = {valor:null,messaje:null,fontipe:null,volver:null};
    servicelist ={};
    keys:any;
    session_data:any;
    token:any;
    iduser:any;
    month:any;
    fecha:any;
    idreser:any;
    f1:any;
    f2:any;
    fechas:any;

    constructor(public navCtrl: NavController,public modalCtrl: ModalController, public navParams: NavParams,public _cookieService:CookieService,public api:ApiProvider) {

      this.session_data=JSON.parse(this._cookieService.get('session'));
      this.iduser=this.session_data.ID;
      this.token=this.session_data.token;

      this.fechas=this.navParams.get('datosp');

      this.f1=this.fechas.init;
      this.f2=this.fechas.end;
      this.f1=this.date_format(this.f1);
      this.f2=this.date_format(this.f2);

      this.api.postinfo('/booking/available/?token='+this.session_data.token,this.navParams.get('datosp')).then((res: any) => {
        this.servicelist=res;
        console.log(res);
      });



    }

    date_format(fecha:string){
      let year = fecha.substring(0,4);
      this.month = fecha.substring(4,6);
      let day = fecha.substring(6,8);

      let lista=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

      this.month=parseInt(this.month);
      this.month=this.month-1;
      this.month=lista[this.month]
      return {
        fecha:  day +' '+ this.month
      }
    }

    ionViewDidLoad() {
      console.log('ionViewDidLoad MisserviciosPage');
    }

    atras() {
      this.navCtrl.setRoot(CalendarPage);
    }

    goresevaservice(idmio,value,init,end,noches,info,image,title){

     this.idreser=idmio;
      let datosparams = {
        'id':idmio,
        'init':init,
        'end':end,
        'value':value,
        'noches':noches,
        'info':info,
        'title':title,
        'image':image
      }
      this.navCtrl.setRoot(DetailreservaPage,{inforeservacion:datosparams,postreserva:this.navParams.get('datosp')});
    }




  }
