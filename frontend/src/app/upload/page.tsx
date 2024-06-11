import React, { Fragment, useCallback, useState } from "react";
import { useDropzone } from "react-dropzone";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";
import * as yup from "yup";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import { UploadImagesResponse, uploadImages } from "../../api/images.api";
import { v4 as uuidv4 } from "uuid";

const schema = yup
  .object({
    name: yup.string().required().min(3),
    email: yup.string().email().required(),
  })
  .required();

interface IFormInput {
  name: string;
  email: string;
}

interface FileStatus {
  id: string;
  status: "pending" | "success" | "error";
  name: string;
  progress: number;
  errorMessage?: string;
  url?: string;
}

interface MutationContext {
  id: string;
  file: File;
  name: string;
  email: string;
}

const Upload: React.FC = () => {
  const {
    register,
    formState: { errors, isValid },
    getFieldState,
    getValues,
  } = useForm<IFormInput>({
    mode: "onChange",
    resolver: yupResolver(schema),
  });
  const [fileStatuses, setFileStatuses] = useState<FileStatus[]>([]);
  const [isNameDisabled, setNameDisabled] = useState(false);
  const [isEmailDisabled, setEmailDisabled] = useState(false);
  const queryClient = useQueryClient();

  const { mutate } = useMutation<
    UploadImagesResponse,
    Error,
    { file: File; name: string; email: string },
    MutationContext
  >({
    mutationFn: ({ file, name, email }) => uploadImages(file, name, email),
    onMutate: (data) => {
      const id = uuidv4();
      setFileStatuses((prev) => [
        ...prev,
        { id, status: "pending", name: data.file.name, progress: 0 },
      ]);

      return { id, ...data };
    },
    onSuccess: (data, _, context) => {
      setFileStatuses((prev) =>
        prev.map((fileStatus) =>
          fileStatus.id === context.id
            ? {
                ...fileStatus,
                status: "success",
                url: data.image.image_thumbnails["250xauto"],
              }
            : fileStatus
        )
      );
    },
    onError: (error, _, context) => {
      if (context) {
        setFileStatuses((prev) =>
          prev.map((fileStatus) =>
            fileStatus.id === context.id
              ? {
                  ...fileStatus,
                  status: "error",
                  errorMessage: error.message,
                }
              : fileStatus
          )
        );
      }
    },
    onSettled: () => {
      queryClient.invalidateQueries({
        queryKey: ["images"],
      });
    },
  });

  const onDrop = useCallback(
    (acceptedFiles: File[]) => {
      const name = getValues("name");
      const email = getValues("email");

      acceptedFiles.forEach((file: File) => {
        mutate({ file, name, email });
      });
    },
    [mutate, getValues]
  );

  const { getRootProps, getInputProps } = useDropzone({
    accept: {
      "image/*": [".webp", ".png", ".jpg", ".jpeg", ".gif"],
    },
    onDrop,
  });

  return (
    <div className="flex flex-col items-center justify-center">
      <h1 className="text-4xl font-bold my-5 border-b-2 border-yellow-300 p-2">
        Upload images
      </h1>
      <div className="w-full">
        <form className="mt-5 flex flex-col justify-center">
          <h2 className="mb-2">Type your name and email and press ENTER</h2>
          <div className="flex flex-col gap-3 mx-auto w-6/12">
            <div className="flex flex-col">
              <div className="relative">
                <input
                  {...register("name")}
                  className="w-full p-3 bg-white rounded border-2 disabled:opacity-50"
                  type="text"
                  placeholder="Jan"
                  onBlur={() => 
                    !getFieldState("name").invalid && setNameDisabled(true)
                  }
                  onKeyUp={(event) => {
                    if (event.key === "Enter") {
                      !getFieldState("name").invalid && setNameDisabled(true);
                    }
                  }}
                  disabled={isNameDisabled}
                />
                {isNameDisabled && (
                  <button
                    type="button"
                    className="absolute text-xs right-0 top-0 m-4 text-gray-500 rounded"
                    onClick={() => setNameDisabled(false)}
                  >
                    Odblokuj
                  </button>
                )}
              </div>
              {errors.name && <p>{errors.name.message}</p>}
            </div>

            <div className="flex flex-col">
              <div className="relative">
                <input
                  {...register("email")}
                  className="w-full p-3 bg-white rounded border-2 disabled:opacity-50"
                  type="text"
                  placeholder="jan.kowalski@gmail.com"
                  onBlur={() =>
                    !getFieldState("email").invalid && setEmailDisabled(true)
                  }
                  onKeyUp={(event) => {
                    if (event.key === "Enter") {
                      !getFieldState("email").invalid && setEmailDisabled(true);
                    }
                  }}
                  disabled={isEmailDisabled}
                />
                {isEmailDisabled && (
                  <button
                    type="button"
                    className="absolute text-xs right-0 top-0 m-4 text-gray-500 rounded"
                    onClick={() => setEmailDisabled(false)}
                  >
                    Odblokuj
                  </button>
                )}
              </div>
              {errors.email && <p>{errors.email.message}</p>}
            </div>
          </div>
          {isValid && isNameDisabled && isEmailDisabled && (
            <div
              {...getRootProps()}
              className="mt-3 p-10 border-dashed rounded border-2"
            >
              <input {...getInputProps()} />
              <p>Drag and drop files here, or click to select files</p>
              <div className="flex flex-wrap gap-5 justify-start">
                {fileStatuses.map((file: FileStatus, index: number) => (
                  <div key={index} className="w-28 mt-3 flex flex-col relative">
                    <div className="relative">
                      {file.status === "error" && (
                        <div className="rounded w-28 h-28 shadow-md hover:shadow-xl border-2 border-zinc-900 bg-red-500 flex items-center justify-center">
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="white"
                            className="h-12 w-12"
                          >
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              strokeWidth={2}
                              d="M6 18L18 6M6 6l12 12"
                            />
                          </svg>
                        </div>
                      )}
                      {file.status === "success" && !!file.url && (
                        <Fragment>
                          <img
                            className="rounded w-28 shadow-md hover:shadow-xl border-2 border-zinc-900"
                            src={file?.url}
                            alt={`Preview ${index}`}
                          />
                          <div className="absolute top-0 left-0 w-full h-full flex items-center justify-center bg-green-500 bg-opacity-50 rounded">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              fill="none"
                              viewBox="0 0 24 24"
                              stroke="white"
                              className="h-12 w-12"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M5 13l4 4L19 7"
                              />
                            </svg>
                          </div>
                        </Fragment>
                      )}
                    </div>
                    <p className="bg-slate-100 text-xs p-1 rounded text-gray-500">
                      {file.name}
                    </p>
                    {file.status === "error" && (
                      <p className="bg-red-500 text-xs p-1 rounded text-white">
                        {file.errorMessage}
                      </p>
                    )}
                  </div>
                ))}
              </div>
            </div>
          )}
        </form>
      </div>
    </div>
  );
};

export default Upload;
