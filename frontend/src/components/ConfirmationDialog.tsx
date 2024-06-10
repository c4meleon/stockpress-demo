import React from 'react';

interface ConfirmDialogProps {
  message: string;
  onConfirm: () => void;
  onCancel: () => void;
}

const ConfirmDialog: React.FC<ConfirmDialogProps> = ({ message, onConfirm, onCancel }) => {
  return (
    <div className="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div className="bg-white p-4 rounded shadow-md max-w-sm w-full">
        <p>{message}</p>
        <div className="flex justify-end space-x-2 mt-4">
          <button className="bg-red-500 text-white px-4 py-2 rounded" onClick={onConfirm}>
            Tak
          </button>
          <button className="bg-gray-300 text-black px-4 py-2 rounded" onClick={onCancel}>
            Nie
          </button>
        </div>
      </div>
    </div>
  );
};

export default ConfirmDialog;
