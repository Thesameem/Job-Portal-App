import axios from "axios";
import Config from "./Config";
import Cookie from "./Cookie";

export const POST = async (uri, formdata) => {
    let Token = Cookie.getCookie('job-app');

    let { data } = await axios.post(Config.baseURL + uri, formdata, {
        headers: {
            Authorization: `Bearer ${Token}`
        }
    });

    return data;
};

export const GET = async (uri) => {
    let Token = Cookie.getCookie('job-app');
    let { data } = await axios.get(Config.baseURL + uri, {
        headers: {
            Authorization: `Bearer ${Token}`
        }
    });

    return data;
}
