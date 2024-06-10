import React, { Fragment, useEffect, useState } from "react";
import { useInfiniteQuery, useQueryClient } from "@tanstack/react-query";
import { deleteImage, downloadImage, getImages } from "../../api/images.api";
import { useInView } from "react-intersection-observer";
import ConfirmDialog from "../../components/ConfirmationDialog";

const Images: React.FC = () => {
  const [showDialogId, setShowDialogId] = useState<number | null>(null);
  const { ref, inView } = useInView();
  const queryClient = useQueryClient();

  const {
    data,
    error,
    fetchNextPage,
    hasNextPage,
    isError,
    isFetching,
    isFetchingNextPage,
  } = useInfiniteQuery({
    queryKey: ["images"],
    queryFn: ({ pageParam }) => getImages({ cursor: pageParam }),
    initialPageParam: "",
    getNextPageParam: (lastPage) => lastPage.next_cursor ?? undefined,
  });

  const handleDownload = async (imageName: string) => {
    console.log(imageName);
    try {
      await downloadImage(imageName);
    } catch (error) {
      console.error("Failed to download image:", error);
    }
  };

  const handleDelete = async (imageId: number) => {
    try {
      await deleteImage(imageId);
      queryClient.invalidateQueries({
        queryKey: ["images"],
      });
      setShowDialogId(null);
    } catch (error) {
      console.error("Failed to delete image:", error);
    }
  };

  const handleCancel = () => {
    setShowDialogId(null);
  };

  useEffect(() => {
    if (inView) {
      fetchNextPage();
    }
  }, [fetchNextPage, inView]);

  if (isFetching && !data) {
    return <p>Loading...</p>;
  }

  if (isError) {
    return <span>Error: {error.message}</span>;
  }

  return (
    <div className="flex flex-col items-center justify-center">
      <h1 className="text-4xl font-bold my-5 border-b-2 border-yellow-300 p-2">
        Images
      </h1>

      <div className="max-w-screen-xl justify-center">
        <ul className="grid grid-cols-4 gap-4">
          {data?.pages.map((page) => (
            <Fragment key={page.next_cursor}>
              {page.data.map(
                ({
                  id,
                  name,
                  email,
                  image_name,
                  image_url,
                  image_thumbnails,
                  image_metadata,
                  image_unique_name,
                }) => (
                  <li
                    key={id}
                    className="flex flex-col items-center justify-center relative"
                  >
                    <a
                      className="flex flex-col items-center justify-center"
                      href={`${image_url}`}
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      <img
                        src={`${image_thumbnails["250x250"]}`}
                        alt={`Thumbnail for ${image_url.split("/").pop()}`}
                        style={{
                          width: "250px",
                          height: "250px",
                          objectFit: "cover",
                          borderRadius: "8px",
                        }}
                      />
                      <p>{image_name}</p>
                    </a>
                    <span>
                      <strong>Uploaded by:</strong>
                      {name} ({email})
                    </span>
                    <span>
                      <strong>Resolution:</strong>
                      {image_metadata.imageResolution.width}x
                      {image_metadata.imageResolution.height}
                    </span>
                    <span>
                      <strong>Extension:</strong>
                      {image_metadata.extension}
                    </span>
                    <button
                      className="bg-gray-100 shadow p-2 rounded absolute top-3 right-24"
                      onClick={() => handleDownload(image_unique_name)}
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        width="24"
                        height="24"
                      >
                        <path d="M21 15c-0.552 0-1 0.448-1 1v3c0 0.551-0.449 1-1 1H5c-0.552 0-1-0.449-1-1v-3c0-0.552-0.448-1-1-1s-1 0.448-1 1v3c0 1.654 1.346 3 3 3h14c1.654 0 3-1.346 3-3v-3C22 15.448 21.552 15 21 15zM11.293 14.707c0.391 0.391 1.023 0.391 1.414 0l5-5c0.391-0.391 0.391-1.023 0-1.414s-1.023-0.391-1.414 0L13 11.586V2c0-0.553-0.448-1-1-1s-1 0.447-1 1v9.586L8.707 8.293c-0.391-0.391-1.023-0.391-1.414 0s-0.391 1.023 0 1.414L11.293 14.707z" />
                      </svg>
                    </button>
                    <button
                      className="bg-gray-100 shadow p-2 rounded absolute top-3 right-10"
                      onClick={() => setShowDialogId(id)}
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        className="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M6 18L18 6M6 6l12 12"
                        />
                      </svg>
                    </button>
                    {showDialogId === id && (
                      <ConfirmDialog
                        message="Czy na pewno chcesz usunąć ten element?"
                        onConfirm={() => handleDelete(id)}
                        onCancel={handleCancel}
                      />
                    )}
                  </li>
                )
              )}
            </Fragment>
          ))}
        </ul>
        <div className="flex justify-center items-center mt-4 mb-4">
          <button
            className="flex"
            ref={ref}
            onClick={() => fetchNextPage()}
            disabled={!hasNextPage || isFetchingNextPage}
          >
            {isFetchingNextPage
              ? "Loading more..."
              : hasNextPage
              ? "Load Newer"
              : "Nothing more to load"}
          </button>
        </div>
        <div>
          {isFetching && !isFetchingNextPage ? "Background Updating..." : null}
        </div>
      </div>
    </div>
  );
};

export default Images;
