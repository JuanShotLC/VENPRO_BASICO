import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { EditreservacionPage } from './editreservacion';

@NgModule({
  declarations: [
    EditreservacionPage,
  ],
  imports: [
    IonicPageModule.forChild(EditreservacionPage),
  ],
  exports: [
    EditreservacionPage
  ]
})
export class EditreservacionPageModule {}
