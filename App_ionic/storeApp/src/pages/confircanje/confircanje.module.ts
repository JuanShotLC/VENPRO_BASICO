import { NgModule } from '@angular/core';
import { IonicPageModule } from 'ionic-angular';
import { ConfircanjePage } from './confircanje';

@NgModule({
  declarations: [
    ConfircanjePage,
  ],
  imports: [
    IonicPageModule.forChild(ConfircanjePage),
  ],
  exports: [
    ConfircanjePage
  ]
})
export class ConfircanjePageModule {}
