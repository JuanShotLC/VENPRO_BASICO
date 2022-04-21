import { Injectable } from '@angular/core';
import { RequestOptions, Http,Headers } from '@angular/http';
import { LoadingController, ToastController } from 'ionic-angular';
import 'rxjs/add/operator/map';

/*
  Generated class for the ApiProvider provider.

  See https://angular.io/docs/ts/latest/guide/dependency-injection.html
  for more info on providers and Angular DI.
*/
@Injectable()
export class ApiProvider {
	 // api_url = 'https://apatxee.com/';
  api_url = 'http://localhost:8000/';
	error_msj:any;
  public api_token:any = {apikey:'!eqBwMfoWmEOWRoz^R^60$p2K'};
  constructor(private toastCtrl: ToastController,public http: Http,public loadingCtrl: LoadingController) {
    console.log(this.api_token);
  }

  login(d:{ email: null, pass: null,token_device:null }):Promise<any>{
    this.error_msj = null;
    let headers = new Headers({
      'Content-Type': 'application/x-www-form-urlencoded'
    });
    let options = new RequestOptions({
      headers: headers
    });
    let body = 'email=' + d.email + '&password=' + d.pass + '&token_device=' + d.token_device;
    let loader = this.loadingCtrl.create({
      content: "Conectando...",
    });
    loader.present();

    return new Promise((resolve)=>{
      this.http.post(this.api_url+'/api/login',body,options).map(res => res.json()).subscribe(data => {
        loader.dismissAll();
        if(data.status=='success')
          resolve(data);
        else(data.status=='fail')
        let toast_error = this.toastCtrl.create({
          message: data.msj,
          duration: 3000,
          position: 'bottom'
        });
        toast_error.present();
        resolve(data);
      });
    });

  }


  // login(d:{ email: null, pass: null,token_device:null }):Promise<any>{
  //   this.error_msj = null;
  //
  //   let headers = new Headers();
  //   headers.append('Content-Type', 'application/json');
  //   // headers.append('apikey', ' !eqBwMfoWmEOWRoz^R^60$p2K');
  //
  //   let body = {
  //     'email':d.email,
  //     'password':d.pass,
  //     };
  //
  //   let valores = JSON.stringify(body);
  //
  //   let loader = this.loadingCtrl.create({
  //     content: "Conectando...",
  //     });
  //   loader.present();
  //
  //
  //   return new Promise((resolve)=>{
  //     console.log(body);
  //
  //
  //
	//    	this.http.post(this.api_url+'/api/login',valores).map(res => res.json()).subscribe(data => {
	//       loader.dismissAll();
  //         console.log(data);
  //         if(data.status_code==200)
  //           resolve(data);
  //       },err=>{
  //         loader.dismissAll();
  //         resolve(err);
  //           let toast_error = this.toastCtrl.create({
  //             message: 'Datos Incorrectos',
  //             duration: 3000,
  //             position: 'bottom'
  //           });
  //           toast_error.present();
  //         console.log('errororororoOR');
	//   	});
  //   });
  // }

  logout(){

    let loader = this.loadingCtrl.create({
      content: "Conectando...",
      });
    loader.present();

    return new Promise((resolve)=>{
       this.http.delete(this.api_url+'/api/login?key='+this.api_token.key+'&token='+this.api_token.token).map(res => res.json()).subscribe(data => {
        loader.dismissAll();
        if(data.status=='success')//here
          resolve(data);
        else(data.status=='fail')
          let toast_error = this.toastCtrl.create({
          message: data.msj,
          duration: 3000,
          position: 'bottom'
          });
          toast_error.present();
          resolve(data);
      });
    });
  }

  getinfo(end:string,d:any,z=true){
    let url_string:string = '';
    let i:any;
    let c:number = 0;
    for(i in d){
      if(c==0)
        url_string += '?'+i+'='+d[i];
      else
        url_string += '&'+i+'='+d[i];
      c++;
    }
    let loader = this.loadingCtrl.create({
      content: "Conectando...",
    });
    if(z){
      loader.present();
    }
    return new Promise((resolve)=>{
      console.log(d);
      console.log(this.api_url+url_string);
      this.http.get(this.api_url+end+url_string).map(res => res.json()).subscribe(data=>{
        loader.dismissAll();
        resolve(data);
      },err=>{
        loader.dismissAll();
        resolve(err);
        console.log('errororororoOR');
      });
    })
  }



  deleteinfo(end:string,d:any,z=true){
    let headers = new Headers();
    headers.append('Content-Type', 'application/json');
    let url_string:string = '';
    let i:any;
    let c:number = 0;
    for(i in d){
      if(c==0)
        url_string += '?'+i+'='+d[i];
      else
        url_string += '&'+i+'='+d[i];
      c++;
    }
    let loader = this.loadingCtrl.create({
      content: "Conectando...",
    });
    if(z){
      loader.present();
    }
    return new Promise((resolve)=>{
      console.log(d);
      console.log(this.api_url+url_string);
      this.http.delete(this.api_url+end+url_string,headers).map(res => res.json()).subscribe(data=>{
        loader.dismissAll();
        resolve(data);
      });
    })
  }

  deleteinfo2(end:string,d:any,z=true){
    let headers = new Headers();
    headers.append('Content-Type', 'application/json');
    let url_string:string = '';
    let i:any;
    let c:number = 0;
    for(i in d){
      if(c==0)
        url_string += '?'+i+'='+d[i];
      else
        url_string += '&'+i+'='+d[i];
      c++;
    }
    let loader = this.loadingCtrl.create({
      content: "Conectando...",
    });
    if(z){
      loader.present();
    }
    return new Promise((resolve)=>{

      console.log(d);
      console.log(this.api_url+url_string);
      this.http.delete('https://cors-anywhere.herokuapp.com/'+this.api_url+end,new RequestOptions({
        headers: headers,
        body: {
          "code": d
        }
      })).map(res => res.json()).subscribe(data=>{
        loader.dismissAll();
        resolve(data);
      },err=>{
        loader.dismissAll();
        resolve(err);
        console.log('errororororoOR');
      });
    })
  }


  putinfo(end:string,d:any,z=true){
    let headers = new Headers();
    headers.append('Content-Type', 'application/json');

    let url_string = d;
    /* let i:any;
     let c:number = 0;
     for(i in d){
       if(c==0)
         url_string += '?'+i+'='+d[i];
       else
         url_string += '&'+i+'='+d[i];
       c++;
     }*/
    let data=d;
    let loader = this.loadingCtrl.create({
      content: "Conectando...",
    });
    loader.present();

    return new Promise((resolve)=>{
      //this.http.put('https://cors-anywhere.herokuapp.com/http://api.indexdigital.co/v1/user/53/?token=123d73652fef402a996eab9b2e81481b', JSON.stringify(body),options)
      //  .map(res => res.json())
      this.http.put(this.api_url+end,data,headers).map(res => res.json()).subscribe(data=>{
        console.log(data);
        loader.dismissAll();
        resolve(data);
      },err=>{
        loader.dismissAll();
        resolve(err);
        console.log('errororororoOR');
      })
    })
  }

  postinfo(end:string,d:any,z=true){
    let headers = new Headers({
      'Content-Type': 'application/x-www-form-urlencoded'
    });
    let options = new RequestOptions({
      headers: headers
    });
    let url_string:string = '';
    let i:any;
    let c:number = 0;
    for(i in d){
      if(c==0)
        url_string += '?'+i+'='+d[i];
      else
        url_string += '&'+i+'='+d[i];
      c++;
    }
    let loader = this.loadingCtrl.create({
      content: "Conectando...",
    });
    if(z){
      loader.present();
    }

    return new Promise((resolve)=>{
      console.log(this.api_url+url_string);
      this.http.post(this.api_url+end+url_string,options).map(res => res.json()).subscribe(data=>{
        loader.dismissAll();
        resolve(data);
      });
    })
  }


  post2info(end:string,d:any){
  let url_string = d;/*
  let i:any;
     let c:number = 0;
    for(i in d){
      if(c==0)
        url_string += '?'+i+'='+d[i];
       else
        url_string += '&'+i+'='+d[i];
     c++;
     }*/
    let data=d;
     let loader = this.loadingCtrl.create({
       content: "Conectando...",
       });
    loader.present();

     return new Promise((resolve)=>{
       this.http.post(this.api_url+end,data).map(res => res.json()).subscribe(data=>{
         console.log(data);
         loader.dismissAll();
        resolve(data);
       },err=>{
         loader.dismissAll();
         resolve(err);
         console.log('errororororoOR');
       })
     })
   }

}
