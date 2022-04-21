import { Component } from '@angular/core';
import {AlertController, IonicPage, NavController, NavParams} from 'ionic-angular';
import {PerfilPage} from "../perfil/perfil";
import {ApiProvider} from "../../providers/api/api";
import {CookieService} from "angular2-cookie/core";
import {EditreservaPage} from "../editreserva/editreserva";


/**
 * Generated class for the MisserviciosPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-misservicios',
  templateUrl: 'misservicios.html'

})
export class MisserviciosPage {

  servicelist ={};
  keys:any;
  session_data:any;
  token:any;
  iduser:any;
  month:any;
  fecha:any;
  valuenull:any=true;
  constructor(public alertCtrl: AlertController,public navCtrl: NavController, public navParams: NavParams,public _cookieService:CookieService,public api:ApiProvider) {

    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.iduser=this.session_data.ID;
    this.token=this.session_data.token;

    this.api.getinfo('/service/' ,{token:this.token}).then((res: any) => {
      this.servicelist = res;
      this.keys = Object.keys(this.servicelist);
      console.log(this.servicelist);

      let someArray = res.data;
      console.log(someArray);

      someArray.forEach(element => {
        let someArray2 = element.reservation;
        if(someArray2.length>0) {
          someArray2.forEach(element2 => {
            if (element2.ID == this.session_data.ID) {
              this.valuenull = false;
            }
          })
        }
      });


    });



  }

  date_format(fecha:string){
    let year = fecha.substring(0,4);
    this.month = fecha.substring(4,6);
    let day = fecha.substring(6,8);

    /*let date_full = new Date(year-month-day);
    Date.parse(month);
    var mes = date_full.getMonth()+1;*/

    /*let lista=['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];*/

    return {
     fecha:  day +'/'+ this.month +'/'+ year
    }
  }


  atras() {
    this.navCtrl.setRoot(PerfilPage);
  }
  delete(id,code){
    console.log(id,code);

    let alert = this.alertCtrl.create({
      title: '¿Estas seguro?',
      message: 'Realmente quieres eliminar la reservación',

      buttons: [
        {
          text: 'Cancelar',
          handler: (data: any) => {
            console.log('Cancel clicked');
          }
        },
        {
          text: 'Eliminar',
          handler: (data: any) => {
            this.api.deleteinfo2('/service/'+id+'/?token='+this.token,code).then((res: any) => {

              if(res.data==true){
                this.api.getinfo('/service/' ,{token:this.token}).then((res: any) => {
                  this.servicelist = res;
                  this.keys = Object.keys(this.servicelist);
                  console.log(this.servicelist);
                });
              }
            });
          }
        }
      ]
    });

    alert.present();

  }
  goresevaservice(idmio,codigo,init,hour){
    this.navCtrl.setRoot(EditreservaPage,{id:idmio,codigo:codigo,date:init,hour:hour});
  }

}
