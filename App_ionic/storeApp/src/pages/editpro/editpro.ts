import { Component } from '@angular/core';
import {IonicPage, NavController, NavParams, ToastController} from 'ionic-angular';
import {PerfilPage} from "../perfil/perfil";
import {ApiProvider} from "../../providers/api/api";
import {CookieService} from "angular2-cookie/core";
import {IMyDrpOptions} from "mydaterangepicker";
import {DropzoneConfigInterface} from "ngx-dropzone-wrapper";
import {PayPal, PayPalConfiguration, PayPalPayment} from "@ionic-native/paypal";

/**
 * Generated class for the EditproPage page.
 *
 * See http://ionicframework.com/docs/components/#navigation for more info
 * on Ionic pages and navigation.
 */
@IonicPage()
@Component({
  selector: 'page-editpro',
  templateUrl: 'editpro.html',
})
export class EditproPage {
  session_data:any;
  datos:any;
  id:any;
  titulo:any;
  precio:any;
  divisa:any;
  categoriaslist:any;
  categoria:any;
  descripcion:any;
  myDateRangePickerOptions: IMyDrpOptions = {}
  private model: any;
  date_begin:any;
  date_end:any;
  hour_init: any;
  hour_finish: any;
  dias:any;
  date_init:any;
  date_finish:any;
  token:any;
  empoint:any;
  imagess: any;
  pay:any =1;
  public config: DropzoneConfigInterface = {
    clickable: true,
    maxFiles: 4,
    autoReset: null,
    paramName: 'photo',
    errorReset: null,
    cancelReset: null,
    acceptedFiles : 'image/jpeg, images/jpg, image/png'
  };
  imgtrue:any =false;
  constructor(private payPal: PayPal, public navCtrl: NavController, public navParams: NavParams, public _cookieService:CookieService,public api:ApiProvider, public toastCtrl: ToastController) {

    this.session_data = JSON.parse(this._cookieService.get('session'));
    this.token = this.session_data.token;
    this.empoint = this.api.api_url;
    this.config.url = this.empoint + 'api/upload';
    this.id = this.navParams.get('id');

    console.log(this.id);
    if (this.id) {
      this.config.params = {id: this.id, act: 'addpro'};
      this.loadData(this.id);
    }
    this.api.getinfo('/api/listarcate', null).then((res: any) => {
      if(res){
        this.categoriaslist=res.categorias;
        console.log(this.categoriaslist);
      }

    });
    let dt = new Date();
    let month = dt.getMonth()+1;
    let day = dt.getDate()-1;
    let year = dt.getFullYear();

    this.date_begin={year:year,month:month,day:day};
    this.model = {beginDate:  null,
      endDate: null};

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
    this.dias=[{"day":"Monday","status":"false"},
      {"day":"Tuesday","status":"false"},
      {"day":"Wednesday","status":"false"},
      {"day":"Thursday","status":"false"},
      {"day":"Friday","status":"false"},
      {"day":"Saturday","status":"false"},
      {"day":"Sunday","status":"false"}];
  }
  loadData(id){
    this.api.getinfo('/api/editarpro/'+id, null ).then((res: any) => {
      this.datos = res;
      this.titulo = this.datos.data.titulo;
      this.precio = this.datos.data.precio;
      this.divisa = this.datos.data.tipo_moneda;
      this.categoria = this.datos.categorias.categorias;
      this.descripcion = this.datos.data.descripcion;
      this.date_init= this.datos.data.date_init;
      this.date_finish= this.datos.data.date_finish;
      this.hour_init = this.datos.data.hour_init;
      this.hour_finish=this.datos.data.hour_finish;
      if(this.datos.data.destacado==0){
        this.pay=1;
      }else{
        this.pay=2;
      }

      this.dias= JSON.parse(this.datos.data.dais);
      this.model = {beginDate:  this.date_init = this.camfecha(this.date_init),
        endDate: this.camfecha(this.date_finish)};
      this.imagess = this.datos.img; this.imagess = this.datos.img;
    });
  }
  date_formcalendar(data_string:any){
    let year = data_string.year;
    let month = data_string.month;
    let day = data_string.day;
    //const fechanew = new Date(year , month , day).toString();
    const fechanew1 = year+"-"+month+"-"+day;
    const fechanew2 = Date.parse(fechanew1);
    let fechanew = new Date(fechanew2);
    return fechanew.toDateString();
  }
  public onUploadSuccess(args: any): void {
    console.log('exito', args);
    this.imgtrue = true;
    this.api.getinfo('/api/editarpro/'+this.id, null,null ).then((res: any) => {
      this.imagess = res.img;
    });
  }
  public onUploadError(args: any): void {
    this.presentToast("Ocurrio un error con la imagen");
  }
  borrari(id){
    const body  ={
      id:id
    }
    console.log(id);
    console.log(body);
    this.api.deleteinfo('api/eliminarimage/', body,null ).then((res: any) => {
      if(res.status=='success'){
        this.api.getinfo('/api/editarpro/'+this.id, null,null ).then((res: any) => {
          this.imagess = res.img;
        });
      }
    });
  }


  pagarpaypal(){
    //paypal

    this.payPal.init({
      // PayPalEnvironmentProduction: 'AfET0O3qTR1WYHXeKCtct_2kWe4unNCCh2gAOJgHAzQWNCJe-cFhajbcuC9F1EVLxgFIduAH-G8SLzKV',
      // PayPalEnvironmentSandbox: 'AfET0O3qTR1WYHXeKCtct_2kWe4unNCCh2gAOJgHAzQWNCJe-cFhajbcuC9F1EVLxgFIduAH-G8SLzKV'
      PayPalEnvironmentProduction: 'AVPHmPp7pfTorrg3iHl5VUFNLV0EfAeO7Cf4FPB3YVf1H_t1mPCp9o1cox4w9vOOgGrs-lgAX_xty9Pq',
      PayPalEnvironmentSandbox: 'AcHGE9Bs9g8flW5aHvIo_8rBpEHnVlnE_5NdNh_sZ_C1JnS_6FLY4Pal2qO7t7OgS2EIQwcpdiuktheC'
    }).then(() => {
      // Environments: PayPalEnvironmentNoNetwork, PayPalEnvironmentSandbox, PayPalEnvironmentProduction
      this.payPal.prepareToRender('PayPalEnvironmentProduction', new PayPalConfiguration({
        // Only needed if you get an "Internal Service Error" after PayPal login!
        //payPalShippingAddressOption: 2 // PayPalShippingAddressOptionPayPal
      })).then(() => {
        let payment = new PayPalPayment('3.00', 'EUR', 'Destacar producto', 'sale');
        this.payPal.renderSinglePaymentUI(payment).then((res:any) => {
          // Successfully paid
          console.log(payment);
          console.log(res);
          this.pay=2;
          /* this.api.post2info('/api/listarattr',{'attr':this.datattributos}).then(function(res:any){

          if( res.status=='success'){
            this.atributo = res.atributos;
            let arreglo = res.atributos;
            console.log(this.attrs);
          }
      });*/
          //aqui movi algo
          this.presentToast("Producto destacado exitosamente");
          this.api.postinfo('api/destacar',{id_pro: this.id }).then(function(res:any){
            console.log(res);
            this.pay=2;
          });
          // Example sandbox response
          //
          // {
          //   "client": {
          //     "environment": "sandbox",
          //     "product_name": "PayPal iOS SDK",
          //     "paypal_sdk_version": "2.16.0",
          //     "platform": "iOS"
          //   },
          //   "response_type": "payment",
          //   "response": {
          //     "id": "PAY-1AB23456CD789012EF34GHIJ",
          //     "state": "approved",
          //     "create_time": "2016-10-03T13:33:33Z",
          //     "intent": "sale"
          //   }
          // }
        }, () => {
          this.presentToast("Erro de render ");
          // Error or render dialog closed without being successful
        });
      }, () => {
        // Error in configuration
        this.presentToast("Error de confuguración");
      });
    }, () => {
      this.presentToast("Error de inicializacion");
      // Error in initialization, maybe PayPal isn't supported or something else
    });
  }


  camfecha(f) {
    let dt = new Date(f);
    let month = dt.getMonth()+1;
    let day = dt.getDate()-1;
    let year = dt.getFullYear();
    return {year:year,month:month,day:day};
  }
  ionViewDidLoad() {
    console.log('http://localhost:8000/api/editarpro/170');
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
  editar() {

    if (!this.titulo) {
      this.presentToast("Nombre requerido");
    }
    else if (!this.precio) {
      this.presentToast("Precio requerido");
    }
    else if (!this.descripcion) {
      this.presentToast("Descripcion requerida");
    }
    else if (!this.divisa) {
      this.presentToast("Divisa requerida");
    }
    else if (!this.hour_init) {
      this.presentToast("Hora de inico requerida");
    }
    else if (!this.hour_finish) {
      this.presentToast("Hora Final requerida");
    }
    else if (!this.model.endDate) {
      this.presentToast("Fecha final requerida");
    }
    else {
      this.date_init = this.date_formcalendar(this.model.beginDate);
      this.date_finish = this.date_formcalendar(this.model.endDate);
      if (!this.date_finish) {
        this.presentToast("Fecha final requerida");
      } else {
        let body = {
          'titulo': this.titulo,
          'precio': this.precio,
          'descripcion': this.descripcion,
          'categoria': this.categoria,
          'divisa': this.divisa,
          'id_user': this.session_data.id,
          'id': this.id,
          'date_init': this.date_init,
          'date_finish': this.date_finish,
          'hour_init': this.hour_init,
          'hour_finish': this.hour_finish,
          'dais': this.dias
        }
        console.log(body);

        this.api.putinfo('api/addproducto', body).then((res: any) => {
          if (res.status == 'success') {
            this.presentToast("Editado con exito");
          } else {
            this.presentToast("Error, Vuelve a intentarlo");
          }
        });
      }
    }
  }

}
