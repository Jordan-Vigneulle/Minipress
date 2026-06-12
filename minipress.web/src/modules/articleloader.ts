'use strict';
import { url_categories } from './config';



export async function loadAll<T>(url: string, uri?: string, idPicture?: string): Promise<T> {
    let fullUrl: string;

    if (uri) {
        fullUrl = url + uri;
    } else {
        fullUrl = url;
    }

    return fetch(fullUrl)
        .then((response: Response): Promise<T> => {
            if (response.ok) {
                return response.json() as Promise<T>;
            }

            console.log('Response error : ' + response.status);
            return Promise.reject(new Error(response.statusText));
        })
        .catch((error: Error) => {
            console.log("Erreur : " + error);
            return Promise.reject(error);
        });
}

// export async function loadCategories<T>(id: number): Promise<T> {
//     return fetch(url_categories)
//         .then((response: Response): Promise<T> => {
//             if (response.ok) {
//                 return response.json() as Promise<T>;
//             }
//             console.log('Response error : ' + response.status);
//             return Promise.reject(new Error(response.statusText));
//         })
//         .catch((error: Error) => {
//             console.log("Erreur : " + error);
//             return Promise.reject(error);
//         });
// }
    