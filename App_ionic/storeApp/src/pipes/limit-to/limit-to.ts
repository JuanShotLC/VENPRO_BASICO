import { Pipe, PipeTransform } from '@angular/core';

/**
 * Generated class for the LimitToPipe pipe.
 *
 * See https://angular.io/docs/ts/latest/guide/pipes.html for more info on
 * Angular Pipes.
 */
@Pipe({
  name: 'limitto',
})
export class LimitToPipe implements PipeTransform {
  /**
   * Takes a value and makes it lowercase.
   */
  transform(value: string, args: string[]) : string {
    console.log(args);
    let limit = args.length > 0 ? parseInt(args[0], 10) : 10;
    let trail = args.length > 1 ? args[1] : '...';

    return value.length > limit ? value.substring(0, limit) + trail : value;
  }
}
