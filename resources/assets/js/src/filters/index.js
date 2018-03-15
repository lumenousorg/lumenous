import moment from 'moment'
        import config from '../config'


        const urlParser = document.createElement('a')

export function domain(url) {
    urlParser.href = url
    return urlParser.hostname
}

export function count(arr) {
    return arr.length
}

export function prettyDate(date) {
    var a = new Date(date)
    return a.toDateString()
}

export function prettyDateTime(date) {
    return moment(date).format('MMM DD YY HH:mm a z');
}

export function pluralize(time, label) {
    if (time === 1) {
        return time + label
    }

    return time + label + 's'
}

export function toLumens(stroops) {
    return stroops == 0 || stroops == null ? 0 : stroops / config.stroop_scale;
}
