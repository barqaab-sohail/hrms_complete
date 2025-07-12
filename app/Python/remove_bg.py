import os
import sys
from pathlib import Path
from rembg import remove, new_session
from PIL import Image, ImageEnhance, ImageFilter
import io

MODEL_DIR = Path(__file__).parent / "rembg_models"
os.environ["U2NET_HOME"] = str(MODEL_DIR)

def debug_log(message):
    """Print debug messages to stderr"""
    print(f"DEBUG: {message}", file=sys.stderr)

def validate_environment():
    """Check all requirements are met"""
    # Verify model directory exists
    if not MODEL_DIR.exists():
        debug_log(f"Model directory missing: {MODEL_DIR}")
        return False
    
    # Verify at least one model exists
    model_files = list(MODEL_DIR.glob("*.onnx"))
    if not model_files:
        debug_log("No model files found (*.onnx)")
        return False
    
    debug_log(f"Found models: {[m.name for m in model_files]}")
    return True

def remove_background(input_path, output_path):
    try:
        if not validate_environment():
            return False

        debug_log(f"Processing: {input_path}")
        
        # Verify input file exists
        if not os.path.exists(input_path):
            debug_log("Input file not found")
            return False

        # Read input file
        with open(input_path, 'rb') as f:
            input_data = f.read()
            debug_log(f"Read {len(input_data)} bytes")

        # Process with debugging
        debug_log("Starting background removal...")
        output_data = remove(
            input_data,
            session=new_session("u2net"),
            alpha_matting=True,
            alpha_matting_foreground_threshold=240,
            alpha_matting_background_threshold=10,
            alpha_matting_erode_size=10
        )
        debug_log(f"Got {len(output_data)} bytes output")

        if not output_data:
            debug_log("Empty output from rembg")
            return False

        # Verify output is valid PNG
        try:
            img = Image.open(io.BytesIO(output_data))
            debug_log(f"Output image: {img.size} {img.mode}")
            img.verify()  # Verify it's a valid image
            debug_log("Image verified successfully")
        except Exception as e:
            debug_log(f"Invalid output image: {str(e)}")
            return False

        # Save output
        with open(output_path, 'wb') as f:
            f.write(output_data)
        debug_log(f"Saved to: {output_path}")

        return True

    except Exception as e:
        debug_log(f"Error: {str(e)}")
        return False

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python remove_bg.py <input_path> <output_path>", file=sys.stderr)
        sys.exit(1)
        
    success = remove_background(sys.argv[1], sys.argv[2])
    sys.exit(0 if success else 1)