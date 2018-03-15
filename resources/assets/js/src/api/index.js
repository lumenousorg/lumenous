import axios from 'axios'
        import config from '../config'

        export default {
            request(method, uri, data = null, prefix = null) {
                if (!method) {
                    console.error('API function call requires method argument')
                    return
                }

                if (!uri) {
                    console.error('API function call requires uri argument')
                    return
                }

                var url = (prefix == null ? config.serverURI : prefix) + uri
                var headers = {};
                if (window.localStorage) {
                    var token = window.localStorage.getItem('token')
                    headers.Authorization = token;
                }
                return axios({method, url, data, headers: headers})
            }
        }
