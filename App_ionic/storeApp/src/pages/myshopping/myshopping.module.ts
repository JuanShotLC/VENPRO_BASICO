import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { MyshoppingPage } from './myshopping';

@NgModule({
  declarations: [
    MyshoppingPage,
  ],
  imports: [
    IonicPageModule.forChild(MyshoppingPage),
  ],
  exports: [
    MyshoppingPage
  ]
})
export class MyshoppingPageModule {}
