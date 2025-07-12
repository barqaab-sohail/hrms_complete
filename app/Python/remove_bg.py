import sys
from rembg import remove
from PIL import Image
import io

def remove_background(input_path, output_path):
    try:
        # Open input image
        with open(input_path, 'rb') as f:
            input_image = f.read()
        
        # Remove background
        output_image = remove(input_image)
        
        # Save output
        with open(output_path, 'wb') as f:
            f.write(output_image)
            
        return True
    except Exception as e:
        print(f"Error: {str(e)}", file=sys.stderr)
        return False

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python remove_bg.py <input_path> <output_path>", file=sys.stderr)
        sys.exit(1)
        
    success = remove_background(sys.argv[1], sys.argv[2])
    sys.exit(0 if success else 1)