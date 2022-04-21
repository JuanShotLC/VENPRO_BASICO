import { Pipe, PipeTransform } from '@angular/core';

/**
 * Generated class for the FilterForPipe pipe.
 *
 * See https://angular.io/docs/ts/latest/guide/pipes.html for more info on
 * Angular Pipes.
 */
@Pipe({
  name: 'filterfor',
})
export class FilterForPipe implements PipeTransform {
  /**
   * Takes a value and makes it lowercase.
   */
    transform(value: any, args: any[] = null): any {
      let keys = [];
      for (let key in value) {
          keys.push({key: key, value: value[key]});
      }
      console.log(keys);
      return keys;
    }
}
