import axios from 'axios';

axios.defaults.baseURL = 'http://localhost';

export interface FetchImagesParams {
  cursor?: string;
  pageSize?: number;
  thumbnailSize?: number;
}

interface ImageThumbnail {
  '250x250': string;
}

interface ImageMetadata {
  exif?: any;
  fileSize: number;
  imageResolution: {
    width: number;
    height: number;
  };
  extension: string;
  temperature: number;
}

export interface ImageData {
  name: string;
  email: string;
  image_name: string;
  image_unique_name: string;
  image_url: string;
  image_thumbnails: ImageThumbnail;
  image_metadata: ImageMetadata;
  updated_at: string;
  created_at: string;
  id: number;
}

export interface FetchImagesResponse {
  next_cursor: string;
  next_page_url: string;
  path: string;
  per_page: number;
  data: ImageData[];
}

export interface UploadImagesResponse {
  message: string;
  image: ImageData;
}

export interface UploadImagesError {
  message: string;
  errors: {
    image: string[];
  };
}

export const getImages= async ({cursor, pageSize}: FetchImagesParams): Promise<FetchImagesResponse> => {
  const url = new URL("/api/images", axios.defaults.baseURL);

  if (cursor) {
    url.searchParams.append("cursor", cursor.toString());
  }

  if (pageSize) {
    url.searchParams.append("pageSize", pageSize.toString());
  }

  const res = await axios.get(url.toString());
  return res.data;
};

export const uploadImages = async (file: File, name: string, email: string): Promise<UploadImagesResponse> => {
  const formData = new FormData();
  formData.append(`image`, file);
  formData.append('name', name);
  formData.append('email', email);

  try {
    const res = await axios.post('/api/images/upload', formData);

    if (res.status !== 200) {
      throw new Error("Network response was not ok");
    }

    return res.data;
  } catch (error) {
    if (axios.isAxiosError(error)) {
      if (!error.response) {
        throw new Error("Network Error");
      }

      const serverError = error.response?.data as UploadImagesError;
      throw new Error(serverError.message);
    }
    throw error;
  }
};

export const deleteImage = async (id: number): Promise<{ message: string }> => {
  try {
    const res = await axios.delete(`/api/images/${id}`);

    if (res.status !== 200) {
      throw new Error("Network response was not ok");
    }

    return res.data;
  } catch (error) {
    if (axios.isAxiosError(error)) {
      if (!error.response) {
        throw new Error("Network Error");
      }

      throw new Error(error.response.data.message);
    }
    throw error;
  }
};

export const downloadImage = async (imageName: string): Promise<void> => {
  try {
    const res = await axios.get(`/api/images/download/${imageName}`, {
      responseType: 'blob',
    });

    if (res.status !== 200) {
      throw new Error("Network response was not ok");
    }

    const url = window.URL.createObjectURL(new Blob([res.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', imageName);
    document.body.appendChild(link);
    link.click();
    link.remove();
  } catch (error) {

    if (axios.isAxiosError(error)) {
      if (!error.response) {
        throw new Error("Network Error");
      }

      throw new Error(error.response.data.message);
    }
    throw error;
  }
}