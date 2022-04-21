import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { ConfirdeletePage } from './confirdelete';

@NgModule({
  declarations: [
    ConfirdeletePage,
  ],
  imports: [
    IonicPageModule.forChild(ConfirdeletePage),
  ],
  exports: [
    ConfirdeletePage
  ]
})
export class ConfirdeletePageModule {}
