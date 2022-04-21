import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { ReferidoPage } from './referido';

@NgModule({
  declarations: [
    ReferidoPage,
  ],
  imports: [
    IonicPageModule.forChild(ReferidoPage),
  ],
  exports: [
    ReferidoPage
  ]
})
export class ReferidoPageModule {}
