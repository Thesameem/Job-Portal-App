import axios from "axios";
import Config from "./Config";
import Cookie from "./Cookie";


export const POST = async (uri, formdata, isMultipart = false) => {
    try {
        let Token = Cookie.getCookie('job-app');
        console.log(`[Fetch] POST request to ${Config.baseURL + uri}`);
        console.log(`[Fetch] Token exists: ${!!Token}`);
        console.log(`[Fetch] Is multipart: ${isMultipart}`);
        
        if (isMultipart) {
            console.log('[Fetch] FormData keys:', [...formdata.keys()]);
            
            // Log company_logo details if it exists
            if (formdata.has('company_logo')) {
                const file = formdata.get('company_logo');
                if (file instanceof File) {
                    console.log('[Fetch] Company logo details:', {
                        name: file.name,
                        type: file.type,
                        size: file.size,
                        lastModified: new Date(file.lastModified).toISOString()
                    });
                } else {
                    console.log('[Fetch] Company logo is not a File object:', typeof file);
                }
            }
            
            // Log if profile_photo exists in FormData
            if (formdata.has('profile_photo')) {
                const file = formdata.get('profile_photo');
                if (file instanceof File) {
                    console.log('[Fetch] Profile photo details:', {
                        name: file.name,
                        type: file.type,
                        size: file.size,
                        lastModified: new Date(file.lastModified).toISOString()
                    });
                } else {
                    console.log('[Fetch] Profile photo is not a File object:', typeof file);
                }
            }
        } else {
            console.log(`[Fetch] Request payload:`, formdata);
        }

        // Set up headers based on content type
        let headers = {
            Authorization: `Bearer ${Token}`
        };

        // For multipart/form-data, let axios set the correct Content-Type with boundary
        if (!isMultipart) {
            // Check if formdata is FormData instance
            if (formdata instanceof FormData) {
                // Convert FormData to JSON object
                const jsonData = {};
                formdata.forEach((value, key) => {
                    jsonData[key] = value;
                });
                formdata = jsonData;
            }
            headers['Content-Type'] = 'application/json';
        }

        // Create the config object
        const config = {
            headers: headers
        };

        // If this is a multipart request, ensure we're not modifying the FormData object
        let payload = formdata;
        if (isMultipart) {
            // FormData is already ready to go - don't stringify it
            console.log('[Fetch] Sending as FormData with automatic Content-Type');
            
            // Additional debugging: print out all form values (excluding file contents)
            console.log('[Fetch] FormData contents:');
            for (const pair of formdata.entries()) {
                const [key, value] = pair;
                if (value instanceof File) {
                    console.log(`${key}: [File] ${value.name}, type=${value.type}, size=${value.size}B`);
                } else {
                    console.log(`${key}: ${value}`);
                }
            }
        } else {
            // For JSON requests, make sure the payload is properly formatted
            payload = formdata;
            console.log('[Fetch] Sending as JSON');
        }

        // Make the request with appropriate headers and payload
        let { data } = await axios.post(Config.baseURL + uri, payload, config);

        console.log(`[Fetch] POST response from ${uri}:`, data);

        // Check if the response already has an error property
        if (data && typeof data === 'object' && 'error' in data) {
            return data;
        }
        
        // If the response doesn't have an error property, display it with error: false
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
            reason: error.response?.data?.reason || error.response?.data?.message || error.message || 'Network error occurred',
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
