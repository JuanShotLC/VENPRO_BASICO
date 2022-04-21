import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { EditreservaPage } from './editreserva';

@NgModule({
  declarations: [
    EditreservaPage,
  ],
  imports: [
    IonicPageModule.forChild(EditreservaPage),
  ],
  exports: [
    EditreservaPage
  ]
})
export class EditreservaPageModule {}
