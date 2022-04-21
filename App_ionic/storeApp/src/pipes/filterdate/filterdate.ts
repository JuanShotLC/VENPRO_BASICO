import { Pipe, PipeTransform } from '@angular/core';
import { DatePipe } from '@angular/common';
/**
 * Generated class for the FilterdatePipe pipe.
 *
 * See https://angular.io/docs/ts/latest/guide/pipes.html for more info on
 * Angular Pipes.
 */
@Pipe({
  name: 'filterdate',
})
export class FilterdatePipe implements PipeTransform {
  /**
   * Takes a value and makes it lowercase.
   */
  transform(value: string) {
       var datePipe = new DatePipe("en-US");
        value = datePipe.transform(value, 'MM-dd-yyyy');
        return value;
  }
}
