import axios from "axios";
import Config from "./Config";
import Cookie from "./Cookie";

export const POST = async (uri, formdata) => {
    try {
        let Token = Cookie.getCookie('job-app');

        let { data } = await axios.post(Config.baseURL + uri, formdata, {
            headers: {
                Authorization: `Bearer ${Token}`
            }
        });

        // Check if the response already has an error property
        // This handles cases where the backend returns { error: true/false, ... }
        if (data && typeof data === 'object' && 'error' in data) {
            return data;
        }
        
        // If the response doesn't have an error property, structure it with error: false
        return {
            error: false,
            response: data
        };
    } catch (error) {
        // Handle axios errors properly
        console.error(`POST request to ${uri} failed:`, error);
        
        // Return a standardized error object
        return {
            error: true,
            reason: error.response?.data?.message || error.message || 'Network error occurred',
            status: error.response?.status || 500,
            response: error.response?.data || null
        };
    }
};

export const GET = async (uri) => {
    try {
        let Token = Cookie.getCookie('job-app');
        let { data } = await axios.get(Config.baseURL + uri, {
            headers: {
                Authorization: `Bearer ${Token}`
            }
        });

        // Check if the response already has an error property
        // This handles cases where the backend returns { error: true/false, ... }
        if (data && typeof data === 'object' && 'error' in data) {
            return data;
        }
        
        // If the response doesn't have an error property, structure it with error: false
        return {
            error: false,
            response: data
        };
    } catch (error) {
        // Handle axios errors properly
        console.error(`GET request to ${uri} failed:`, error);
        
        // Return a standardized error object
        return {
            error: true,
            reason: error.response?.data?.message || error.message || 'Network error occurred',
            status: error.response?.status || 500,
            response: error.response?.data || null
        };
    }
}
