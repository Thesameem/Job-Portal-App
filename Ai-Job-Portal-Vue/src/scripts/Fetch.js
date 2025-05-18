import axios from "axios";
import Config from "./Config";
import Cookie from "./Cookie";

export const POST = async (uri, formdata) => {
    try {
        let Token = Cookie.getCookie('job-app');
        console.log(`[Fetch] POST request to ${Config.baseURL + uri}`);
        console.log(`[Fetch] Token exists: ${!!Token}`);
        console.log(`[Fetch] Request payload:`, formdata);

        let { data } = await axios.post(Config.baseURL + uri, formdata, {
            headers: {
                Authorization: `Bearer ${Token}`
            }
        });

        console.log(`[Fetch] POST response from ${uri}:`, data);

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
        console.error(`[Fetch] POST request to ${uri} failed:`, error);
        console.error(`[Fetch] Error details:`, { 
            status: error.response?.status, 
            statusText: error.response?.statusText,
            message: error.message,
            data: error.response?.data
        });
        
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
        console.log(`[Fetch] GET request to ${Config.baseURL + uri}`);
        console.log(`[Fetch] Token exists: ${!!Token}`);
        
        let { data } = await axios.get(Config.baseURL + uri, {
            headers: {
                Authorization: `Bearer ${Token}`
            }
        });

        console.log(`[Fetch] GET response from ${uri}:`, data);

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
        console.error(`[Fetch] GET request to ${uri} failed:`, error);
        console.error(`[Fetch] Error details:`, { 
            status: error.response?.status, 
            statusText: error.response?.statusText,
            message: error.message,
            data: error.response?.data
        });
        
        // Return a standardized error object
        return {
            error: true,
            reason: error.response?.data?.message || error.message || 'Network error occurred',
            status: error.response?.status || 500,
            response: error.response?.data || null
        };
    }
}
