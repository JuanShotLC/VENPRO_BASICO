import { Component } from '@angular/core';
import {AlertController, IonicPage, NavController, NavParams} from 'ionic-angular';
import {CookieService} from "angular2-cookie/core";
import {ApiProvider} from "../../providers/api/api";
import {PerfilPage} from "../perfil/perfil";
import {EditreservacionPage} from "../editreservacion/editreservacion";
import {EditproPage} from "../editpro/editpro";

/**
 * Generated class for the MyreservasPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-myreservas',
  templateUrl: 'myreservas.html',
})
export class MyreservasPage {

  servicelist:any
  keys:any;
  session_data:any;
  token:any;
  iduser:any;
  month:any;
  fecha:any;
  valuenull:any=true;
  user: any;
  total:any = 0;
  constructor(public alertCtrl: AlertController,public navCtrl: NavController, public navParams: NavParams,public _cookieService:CookieService,public api:ApiProvider) {

    this.session_data=JSON.parse(this._cookieService.get('session'));
    this.iduser=this.session_data.id;

    this.api.getinfo('/api/productosusr/'+this.iduser,{id:this.iduser}).then((res: any) => {
      this.servicelist = res;
      this.user = res[0].user.name;
      if(this.servicelist.length> 0){
        this.valuenull=false
      }
      this.total=this.servicelist.length;
      this.keys = Object.keys(this.servicelist);
    });

  }
  leer(valor){
    console.log(valor);
    this.valuenull=true;
    console.log("entro ");
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

  ionViewDidLoad() {
    console.log('ionViewDidLoad MisserviciosPage');
  }

  atras() {
    this.navCtrl.setRoot(PerfilPage);
  }
  delete(id){

    let alert = this.alertCtrl.create({
      title: '¿Estas seguro?',
      message: 'Realmente quieres eliminar este producto?',

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
            console.log(id);
            this.api.deleteinfo('api/deletepro/'+id,null).then((res: any) => {
              if(res.status=='success'){
                this.api.getinfo('api/productosusr/'+this.iduser,{id:this.iduser}).then((res: any) => {
                  this.servicelist = res;
                  this.user = res[0].user.name;
                  if(this.servicelist.length> 0){
                    this.valuenull=false
                  }
                  this.total=this.servicelist.length;
                  this.keys = Object.keys(this.servicelist);
                });
              }

            });
          }
        }
      ]
    });

    alert.present();

  }
  goresevaservice(idmio){
    this.navCtrl.setRoot(EditproPage,{id:idmio});
  }
}
