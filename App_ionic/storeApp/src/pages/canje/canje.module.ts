import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { CanjePage } from './canje';

@NgModule({
  declarations: [
    CanjePage,
  ],
  imports: [
    IonicPageModule.forChild(CanjePage),
  ],
  exports: [
    CanjePage
  ]
})
export class CanjePageModule {}
