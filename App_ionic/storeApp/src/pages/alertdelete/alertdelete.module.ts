import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { AlertdeletePage } from './alertdelete';

@NgModule({
  declarations: [
    AlertdeletePage,
  ],
  imports: [
    IonicPageModule.forChild(AlertdeletePage),
  ],
  exports: [
    AlertdeletePage
  ]
})
export class AlertdeletePageModule {}
