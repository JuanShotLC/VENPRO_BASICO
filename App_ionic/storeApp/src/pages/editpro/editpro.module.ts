import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { EditproPage } from './editpro';

@NgModule({
  declarations: [
    EditproPage,
  ],
  imports: [
    IonicPageModule.forChild(EditproPage),
  ],
  exports: [
    EditproPage
  ]
})
export class EditproPageModule {}
