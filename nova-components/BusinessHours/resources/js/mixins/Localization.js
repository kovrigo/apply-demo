export default {
  data() {
    return {
      localization: 
        {
            switchOpen: this.__('businessHours.switchOpen'),
            switchClosed: this.__('businessHours.switchClosed'),
            placeholderOpens: this.__('businessHours.placeholderOpens'),
            placeholderCloses: this.__('businessHours.placeholderCloses'),
            addHours: this.__('businessHours.addHours'),
            open: {
                invalidInput: this.__('businessHours.open.invalidInput'),
                greaterThanNext: this.__('businessHours.open.greaterThanNext'),
                lessThanPrevious: this.__('businessHours.open.lessThanPrevious'),
                midnightNotLast: this.__('businessHours.open.midnightNotLast'),
            },
            close: {
                invalidInput: this.__('businessHours.close.invalidInput'),
                greaterThanNext: this.__('businessHours.close.greaterThanNext'),
                lessThanPrevious: this.__('businessHours.close.lessThanPrevious'),
                midnightNotLast: this.__('businessHours.close.midnightNotLast'),
            },
            t24hours: this.__('businessHours.t24hours'),
            midnight: this.__('businessHours.midnight'),
            days: {
                monday: this.__('businessHours.days.monday'),
                tuesday: this.__('businessHours.days.tuesday'),
                wednesday: this.__('businessHours.days.wednesday'),
                thursday: this.__('businessHours.days.thursday'),
                friday: this.__('businessHours.days.friday'),
                saturday: this.__('businessHours.days.saturday'),
                sunday: this.__('businessHours.days.sunday'),
            },
        },
    };
  },

};
