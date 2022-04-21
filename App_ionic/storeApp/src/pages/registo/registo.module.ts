import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { RegistoPage } from './registo';

@NgModule({
  declarations: [
    RegistoPage,
  ],
  imports: [
    IonicPageModule.forChild(RegistoPage),
  ],
  exports: [
    RegistoPage
  ]
})
export class RegistoPageModule {}
